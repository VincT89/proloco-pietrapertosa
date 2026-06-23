@extends('layouts.app')

@section('title', ($page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? 'Photo acknowledgements' : 'Ringraziamenti fotografici')) . ' · Proloco Pietrapertosana')

@section('content')
    @include('components.section-hero', [
        'title' => $page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? "Photo acknowledgements" : "Ringraziamenti fotografici"),
        'subtitle' => $page?->getTranslation('hero_subtitle') ?? ((app()->getLocale() === 'en') ? "Thanks to those who contributed images and visual materials." : "Un grazie a chi ha contribuito con immagini e materiali visivi."),
        'img' => $page?->heroMedia?->optimizedUrl('hero') ?? asset('images/pietrapertosaProloco.jpg')
    ])
    
    @if($page?->getTranslation('intro_text'))
        @include('components.page-intro', [
            'title' => $page?->getTranslation('intro_title'),
            'text' => $page?->getTranslation('intro_text')
        ])
    @endif

    <section class="ed-sec">
        <div class="ed-wrap">
            @php
                $contributors = $page?->data['photo_contributors'] ?? [];
            @endphp
            
            @if(count($contributors) > 0)
                <div class="photo-contributors-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 40px; align-items: stretch; margin-top: 40px;">
                    @foreach($contributors as $contributor)
                        <div class="contributor-card" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.3s ease, border-color 0.3s ease;">
                            @php
                                $logoUrl = null;
                                if (!empty($contributor['logo_media_id'])) {
                                    $media = \App\Models\Media::find($contributor['logo_media_id']);
                                    if ($media) {
                                        $logoUrl = $media->optimizedUrl('small');
                                    }
                                }
                            @endphp
                            @if($logoUrl)
                                <div class="contributor-img" style="height: 220px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); display: flex; align-items: center; justify-content: center; padding: 20px;">
                                    <img src="{{ $logoUrl }}" alt="{{ $contributor['name'] ?? '' }}" style="max-height: 100%; max-width: 100%; object-fit: contain; filter: brightness(0) invert(1);" loading="lazy" decoding="async">
                                </div>
                            @endif
                            <div class="contributor-body" style="padding: 30px; flex: 1; display: flex; flex-direction: column; align-items: center; text-align: center;">
                                <div style="margin: 0 0 15px 0; font-size: 1.1rem; color: var(--gold-soft); font-weight: 600; text-transform: uppercase; letter-spacing: 2px; font-family: var(--font-source-sans), sans-serif;">{{ $contributor['name'] ?? '' }}</div>
                                @php
                                    $desc = (app()->getLocale() === 'en' && !empty($contributor['description_en'])) ? $contributor['description_en'] : ($contributor['description'] ?? '');
                                @endphp
                                @if(!empty($desc))
                                    <div style="font-size: 0.95rem; line-height: 1.6; color: rgba(255, 255, 255, 0.7); margin-bottom: 25px;">
                                        {!! nl2br(e($desc)) !!}
                                    </div>
                                @endif
                                @if(!empty($contributor['website_url']))
                                    <div style="margin-top: auto;">
                                        <a href="{{ $contributor['website_url'] }}" target="_blank" rel="noopener noreferrer" class="ed-btn ed-btn-outline" style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.85rem; padding: 8px 20px; text-transform: uppercase; letter-spacing: 1px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                            {{ (app()->getLocale() === 'en') ? 'Visit website' : 'Sito Web' }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 60px 20px;">
                    <p style="color: #666;">
                        {{ (app()->getLocale() === 'en') ? 'No contributors listed yet.' : 'Nessun contributore elencato al momento.' }}
                    </p>
                </div>
            @endif
        </div>
    </section>
@endsection

