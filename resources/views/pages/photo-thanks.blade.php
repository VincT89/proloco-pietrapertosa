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
                <div class="photo-contributors-grid">
                    @foreach($contributors as $contributor)
                        <div class="contributor-card">
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
                                <div class="contributor-img">
                                    <img src="{{ $logoUrl }}" alt="{{ $contributor['name'] ?? '' }}" loading="lazy" decoding="async">
                                </div>
                            @endif
                            <div class="contributor-body">
                                <h2 class="contributor-name">{{ $contributor['name'] ?? '' }}</h2>
                                @php
                                    $desc = (app()->getLocale() === 'en' && !empty($contributor['description_en'])) ? $contributor['description_en'] : ($contributor['description'] ?? '');
                                @endphp
                                @if(!empty($desc))
                                    <div class="contributor-description">
                                        {!! nl2br(e($desc)) !!}
                                    </div>
                                @endif
                                @if(!empty($contributor['website_url']))
                                    <div class="contributor-action">
                                        <a href="{{ $contributor['website_url'] }}" target="_blank" rel="noopener noreferrer" class="ed-btn ed-btn-outline">
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
                <div class="contributors-empty">
                    <p>
                        {{ (app()->getLocale() === 'en') ? 'No contributors listed yet.' : 'Nessun contributore elencato al momento.' }}
                    </p>
                </div>
            @endif
        </div>
    </section>
@endsection
