@extends('layouts.app')

@php
    
@endphp

@section('title', ($page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? 'Photo Archive' : 'Archivio Fotografico')) . ' · Proloco Pietrapertosana')

@section('content')
    @include('components.section-hero', [
        'title' => $page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? "Photo Archive" : "Archivio Fotografico"),
        'subtitle' => $page?->getTranslation('hero_subtitle') ?? ((app()->getLocale() === 'en') ? "Pietrapertosa in pictures" : "Pietrapertosa in immagini"),
        'img' => $page?->heroMedia?->optimizedUrl('hero') ?? asset('images/pietrapertosaGalleria.jpg')
    ])
    
    <section class="wrap pb-80 pt-40">
        @if(count($albums) > 0)
            <div class="flex-col-60">
                @foreach($albums as $album)
                    @php
                        $allGalleryMedia = $album->galleryMedia->concat($album->externalMedia);
                        $title = $album->getTranslation('title');
                        $dateStr = $album->section_date ? \Carbon\Carbon::parse($album->section_date)->format((app()->getLocale() === 'en') ? 'M d, Y' : 'd/m/Y') : null;
                    @endphp
                    
                    @if(count($allGalleryMedia) > 0)
                        <div>
                            @if($title || $dateStr)
                                <div class="gal-header-wrap mb-20">
                                    <div class="ed-split">
                                        <h2 class="gal-title">{{ $title }}</h2>
                                    </div>
                                    @if($dateStr)
                                        <p class="gal-date">{{ $dateStr }}</p>
                                    @endif
                                </div>
                            @endif
                            
                            <div class="gal-grid mb-60">
                                @php
                                    $galleryJsonData = $allGalleryMedia->map(fn($m) => [
                                        'type' => $m->type,
                                        'provider' => $m->provider,
                                        'url' => $m->type === 'image' ? $m->optimizedUrl('large') : ($m->type === 'video' ? $m->optimizedVideoUrl() : $m->url),
                                        'embed_url' => $m->embed_url
                                    ])->toJson();
                                @endphp
                                <script>
                                    window.galleries = window.galleries || {};
                                    window.galleries['album-{{ $album->id }}'] = {!! $galleryJsonData !!};
                                </script>

                                @foreach($allGalleryMedia->take(8) as $idx => $media)
                                    <div class="cur fad gal-img-wrap pos-rel"
                                         onclick="if(typeof openGallery === 'function') openGallery(window.galleries['album-{{ $album->id }}'], {{ $idx }})"
                                         onmouseenter="this.querySelector('.gal-overlay').style.opacity = '1'"
                                         onmouseleave="this.querySelector('.gal-overlay').style.opacity = '0'">
                                        
                                        <x-media-renderer :media="$media" class="gal-img" />
                                        
                                        @if($media->type === 'video')
                                            <div class="gal-video-badge">
                                                <svg class="gal-video-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Video
                                            </div>
                                        @endif

                                        <div class="gal-overlay">
                                            <span class="gal-overlay-text">{{ $dateStr }}</span>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if($allGalleryMedia->count() > 8)
                                    <div class="cur fad gal-img-wrap pos-rel"
                                         style="display: flex; align-items: center; justify-content: center; background: var(--ink); border: 1px solid var(--gray); border-radius: 8px;"
                                         onclick="if(typeof openGallery === 'function') openGallery(window.galleries['album-{{ $album->id }}'], 8)">
                                        <span style="color: var(--gold); font-size: 1.5rem; font-weight: 600;">+{{ $allGalleryMedia->count() - 8 }} {{ (app()->getLocale() === 'en') ? 'photos' : 'foto' }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div style="margin-top: 40px;">
                {{ $albums->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="gal-empty">
                {{ (app()->getLocale() === 'en') ? "The gallery is currently empty." : "La galleria è momentaneamente vuota." }}
            </div>
        @endif
    </section>

    @include('components.page-cta', [
        'bgImage' => asset('images/sfondo_cta.jpeg'),
        'subtitle' => (app()->getLocale() === 'en') ? "Share your shots" : "Condividi i tuoi scatti",
        'title' => (app()->getLocale() === 'en') ? "Add your photo" : "Aggiungi la tua foto",
        'text' => (app()->getLocale() === 'en') ? "Tag @prolocopietrapertosa on Instagram or Facebook to appear in our gallery." : "Tagga @prolocopietrapertosa su Instagram o Facebook per apparire nella nostra galleria.",
        'buttons' => [
            [
                'text' => (app()->getLocale() === 'en') ? "Follow us on Instagram" : "Seguici su Instagram",
                'link' => "https://www.instagram.com/proloco_pietrapertosa/",
                'iconPath' => '<path d="M15 9h-6a4 4 0 0 0-4 4v6a4 4 0 0 0 4 4h6a4 4 0 0 0 4-4v-6a4 4 0 0 0-4-4z"/><circle cx="12" cy="16" r="3"/><line x1="16.5" y1="12.5" x2="16.5" y2="12.501"/>'
            ],
            [
                'text' => (app()->getLocale() === 'en') ? "Follow us on Facebook" : "Seguici su Facebook",
                'link' => "https://www.facebook.com/prolocopietrapertosa1/?locale=it_IT",
            ]
        ]
    ])

@endsection

