@extends('layouts.app')

@php
    
@endphp

@section('title', ($page?->getTranslation('hero_title') ?? __('news.hero_title')) . ' · Proloco Pietrapertosana')

@section('content')
    @include('components.section-hero', [
        'title' => $page?->getTranslation('hero_title') ?? __('news.hero_title'),
        'subtitle' => $page?->getTranslation('hero_subtitle') ?? __('news.hero_subtitle'),
        'img' => $page?->heroMedia?->optimizedUrl('hero') ?? asset('images/pietrapertosaBacheca.png'),
        'bgPosition' => 'center 30%'
    ])
    
    @include('components.page-intro', [
        'title' => $page?->getTranslation('intro_title') ?? __('news.intro_title'),
        'text' => $page?->getTranslation('intro_text') ?? __('news.intro_text')
    ])

    <section class="wrap pb-80 pt-40">
        <div class="ed-wrap">
            @if(count($news) === 0)
                <div class="nw-empty" style="text-align: center; color: var(--stone); font-style: italic;">
                    {{ (app()->getLocale() === 'en') ? "No news available at the moment." : "Nessuna notizia disponibile al momento." }}
                </div>
            @else
                <div class="ev-card-grid">
                    @foreach($news as $notizia)
                        <div class="ev-card-giant is-clickable" onclick="document.getElementById('news-modal-{{ $notizia->id }}').style.display='flex'">
                            <div class="ev-card-bg">
                                @if($notizia->cover)
                                    <div class="ev-card-normal-bg-inner">
                                        <img src="{{ $notizia->cover->optimizedUrl('card') }}" alt="{{ $notizia->getTranslation('title') }}" class="ev-card-normal-blur" />
                                    </div>
                                    <img src="{{ $notizia->cover->optimizedUrl('card') }}" alt="{{ $notizia->getTranslation('title') }}" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain; z-index: 2;" />
                                @else
                                    <div class="ev-card-placeholder"></div>
                                @endif
                            </div>
                            <div class="ev-card-overlay-right"></div>
                            <div class="ev-card-content">
                                <div class="ev-card-content-inner">
                                    <span class="ev-card-subtitle">
                                        {{ \Carbon\Carbon::parse($notizia->published_at ?? $notizia->created_at)->translatedFormat('d F Y') }}
                                    </span>
                                    <h3 class="ev-card-title">
                                        {{ $notizia->getTranslation('title') }}
                                    </h3>
                                    <p class="ev-card-desc">
                                        {{ Str::limit(strip_tags(html_entity_decode($notizia->getTranslation('excerpt') ?: $notizia->getTranslation('content'))), 150) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Modal News -->
                        <div class="modal lb-modal" id="news-modal-{{ $notizia->id }}" style="display: none; z-index: 1000; padding: 20px;" onclick="this.style.display='none'">
                            <div class="ov"></div>
                            <div class="lb-img-wrap" style="flex-direction: column; align-items: flex-start; justify-content: flex-start; max-width: 800px; width: 100%; background: var(--ink); border-radius: 8px; padding: clamp(20px, 4vw, 40px); cursor: default; overflow-y: auto; max-height: 90vh; position: relative;" onclick="event.stopPropagation()">
                                <button class="x" onclick="document.getElementById('news-modal-{{ $notizia->id }}').style.display='none'" aria-label="Chiudi" style="position: absolute; top: 15px; right: 15px; background: rgba(0,0,0,0.5); border-radius: 50%; border: none; color: white; cursor: pointer; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 10;">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"></path></svg>
                                </button>
                                
                                @if($notizia->cover)
                                    <div style="width: 100%; border-radius: 8px; margin-bottom: 30px; display: flex; justify-content: center; background: var(--ink-2); overflow: hidden;">
                                        <img src="{{ $notizia->cover->optimizedUrl('large') }}" alt="{{ $notizia->getTranslation('title') }}" style="width: 100%; max-height: 400px; object-fit: contain;" />
                                    </div>
                                @endif

                                <h2 class="nw-title" style="margin-top: 0; padding-right: 40px; color: var(--paper); font-family: 'Cormorant Garamond', serif; font-size: clamp(2rem, 3vw, 2.5rem);">{{ $notizia->getTranslation('title') }}</h2>
                                <div class="nw-meta" style="margin-bottom: 24px; color: var(--gold); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">
                                    {{ \Carbon\Carbon::parse($notizia->published_at ?? $notizia->created_at)->translatedFormat('d F Y') }}
                                </div>
                                <div class="nw-excerpt" style="color: rgba(244,239,229,0.9); line-height: 1.6; font-size: 1.05rem; width: 100%;">
                                    {!! clean($notizia->getTranslation('content') ?? '') !!}
                                </div>
                                @php 
                                    $attachments = $notizia->attachmentsMedia;
                                    $allGalleryMedia = $notizia->galleryMedia->concat($notizia->externalMedia);
                                @endphp

                                @if($attachments->count() > 0)
                                    <div class="nw-attachments-wrap" style="margin-top: 30px; width: 100%;">
                                        <h4 style="color: var(--gold); border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Documenti Allegati</h4>
                                        <div style="display: flex; flex-direction: column; gap: 10px;">
                                            @foreach($attachments as $attachment)
                                                <a href="{{ $attachment->url }}" download rel="noopener noreferrer" style="display: flex; align-items: center; background: rgba(255,255,255,0.03); padding: 12px 20px; border-radius: 6px; color: var(--paper); text-decoration: none; border: 1px solid rgba(255,255,255,0.05); transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='rgba(255,255,255,0.03)'">
                                                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right: 15px; color: var(--gold); flex-shrink: 0;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    <span style="flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.95rem;">
                                                        {{ $attachment->alt ?: basename($attachment->url) }}
                                                    </span>
                                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: rgba(255,255,255,0.4); flex-shrink: 0;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($allGalleryMedia->count() > 0)
                                    <div class="nw-gal-wrap" style="margin-top: 40px; width: 100%;">
                                        <h4 class="nw-gal-title" style="color: var(--paper); border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px; margin-bottom: 20px;">@lang('news.gallery_title')</h4>
                                        <div class="nw-gal-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px;">
                                            @foreach($allGalleryMedia as $idx => $media)
                                                <div class="cur fad gal-img-wrap pos-rel" onclick="openGallery({{ $allGalleryMedia->map(fn($m) => ['type' => $m->type, 'provider' => $m->provider, 'url' => $m->type === 'image' ? $m->optimizedUrl('large') : ($m->type === 'video' ? $m->optimizedVideoUrl() : $m->url), 'embed_url' => $m->embed_url])->toJson() }}, {{ $idx }})" style="aspect-ratio: 1; border-radius: 4px; overflow: hidden; position: relative;">
                                                    <x-media-renderer :media="$media" class="gal-img" style="width: 100%; height: 100%; object-fit: cover;" />
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div style="margin-top: 40px;">
                    {{ $news->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </section>
@endsection
