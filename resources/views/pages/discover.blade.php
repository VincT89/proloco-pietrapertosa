@extends('layouts.app')

@php
@endphp

@section('title', ($page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? 'Discover and Live' : 'Scopri e Vivi')) . ' · Proloco Pietrapertosana')

@section('content')
    @include('components.section-hero', [
        'title' => $page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? "Discover and Live" : "Scopri e Vivi"),
        'subtitle' => $page?->getTranslation('hero_subtitle') ?? ((app()->getLocale() === 'en') ? "A journey through the Lucanian Dolomites" : "Un viaggio tra le Dolomiti Lucane"),
        'img' => $page?->heroMedia?->optimizedUrl('hero') ?? asset('images/PietrapertosaScopri.jpg')
    ])
    
    @include('components.page-intro', [
        'title' => $page?->getTranslation('intro_title') ?? ((app()->getLocale() === 'en') ? "Must-see places" : "I luoghi imperdibili"),
        'text' => $page?->getTranslation('intro_text') ?? ((app()->getLocale() === 'en') ? "Explore the historical and naturalistic wonders of our village. And when it's time to rest or eat, trust our welcoming facilities." : "Esplora le meraviglie storiche e naturalistiche del nostro paese. E quando è l'ora di riposare o mangiare, affidati alle nostre strutture accoglienti.")
    ])

    <!-- Sezione Luoghi - Senza pulsanti ripetitivi -->
    <section class="wrap scopri-section-1">
    @if($luoghi->count())
        <div class="discover-feature-slider" data-interval="6500">
            @foreach($luoghi as $index => $luogo)
                @php
                    $images = $luogo->galleryMedia->take(5)->values();
                    $galleryData = $images->map(function($m) {
                        return [
                            'url' => $m->optimizedUrl('default') ?? $m->url,
                            'type' => $m->isVideo() ? 'video' : 'image',
                            'provider' => $m->provider,
                            'embed_url' => $m->embed_url,
                            'alt' => $m->alt
                        ];
                    })->toJson();
                    $title = $luogo->getTranslation('title');
                    $subtitle = $luogo->getTranslation('subtitle');
                    $description = $luogo->getTranslation('description');
                    $contact = $luogo->getTranslation('contact_info');
                @endphp

                <article class="discover-feature-slide {{ $index === 0 ? 'is-active' : '' }}" data-index="{{ $index }}">
                    <div class="discover-feature-copy">


                        <h2 class="discover-feature-title">{{ $title }}</h2>

                        @if($subtitle)
                            <p class="discover-feature-subtitle">{{ $subtitle }}</p>
                        @endif

                        @if($description)
                            <div class="discover-feature-description">
                                {!! $description !!}
                            </div>
                        @endif

                        @if($contact)
                            <div class="discover-feature-contact">
                                {!! nl2br(e($contact)) !!}
                            </div>
                        @endif
                    </div>

                    @if($images->count())
                        <div class="discover-feature-media">
                            @foreach($images as $imgIndex => $img)
                                <div class="discover-collage-img discover-collage-img-{{ $imgIndex + 1 }}" onclick="openGallery({{ $galleryData }}, {{ $imgIndex }})" style="cursor: pointer;">
                                    <img
                                        src="{{ $img->optimizedUrl($imgIndex === 0 ? 'large' : 'card') }}"
                                        alt="{{ $title }}"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                </div>
                            @endforeach
                        </div>
                    @endif
                </article>
            @endforeach

            @if($luoghi->count() > 1)
                <div class="discover-feature-controls">
                    <button type="button" class="discover-feature-prev" aria-label="Precedente">←</button>

                    <div class="discover-feature-dots">
                        @foreach($luoghi as $index => $luogo)
                            <button
                                type="button"
                                class="discover-feature-dot {{ $index === 0 ? 'is-active' : '' }}"
                                data-index="{{ $index }}"
                                aria-label="Vai al luogo {{ $index + 1 }}"
                            ></button>
                        @endforeach
                    </div>

                    <button type="button" class="discover-feature-next" aria-label="Successivo">→</button>
                </div>
            @endif
        </div>
    @endif

        <div class="scopri-cta-wrap">
            <p class="scopri-cta-text">{{ (app()->getLocale() === 'en') ? "Want to explore the itineraries step by step?" : "Vuoi esplorare gli itinerari passo dopo passo?" }}</p>
            <a href="https://www.borgoracconta.it/citta/pietrapertosa/" target="_blank" rel="noopener noreferrer" class="ed-btn scopri-btn-solid">
                {{ (app()->getLocale() === 'en') ? "Visit Borgo Racconta portal" : "Visita il portale Borgo Racconta" }}
            </a>
        </div>
    </section>

    <!-- Sezione Ospitalità e Ristorazione - Redirect a Borgo Racconta -->
    <section class="wrap scopri-section-2">
        <div class="scopri-box-outline">
            <h2 class="scopri-box-title">
                {{ (app()->getLocale() === 'en') ? "Where to Eat and Sleep" : "Dove Mangiare e Dormire" }}
            </h2>
            <p class="scopri-box-desc">
                {{ (app()->getLocale() === 'en') ? "Looking for a cozy place to rest or want to taste typical dishes in the village restaurants? The official and constantly updated list of all accommodation and dining facilities in Pietrapertosa is managed directly on the tourist portal." : "Cerchi un posto accogliente dove riposare o vuoi gustare i piatti tipici nei ristoranti del paese? L'elenco ufficiale e costantemente aggiornato di tutte le strutture ricettive e della ristorazione di Pietrapertosa è curato direttamente sul portale turistico." }}
            </p>
            <a href="https://www.borgoracconta.it/citta/pietrapertosa/" target="_blank" rel="noopener noreferrer" class="ed-btn scopri-btn-outline">
                {{ (app()->getLocale() === 'en') ? "Check the list on Borgo Racconta" : "Consulta l'elenco su Borgo Racconta" }}
            </a>
        </div>
    </section>

    <!-- Sezione Servizi -->
    <section class="wrap scopri-section-3">
        <h2 class="scopri-info-title">
            {{ (app()->getLocale() === 'en') ? "Useful Numbers and Services" : "Numeri e Servizi Utili" }}
        </h2>
        <div class="scopri-grid-2">
            @foreach($servizi as $item)
                <div class="scopri-info-card">
                    <h3 class="scopri-info-card-title">{{ $item->getTranslation('title') }}</h3>
            
                    @if($item->getTranslation('subtitle'))
                        <p class="scopri-info-card-text">{{ $item->getTranslation('subtitle') }}</p>
                    @endif
            
                    @if($item->getTranslation('contact_info'))
                        <div class="scopri-info-card-contact">
                            {!! nl2br(e($item->getTranslation('contact_info'))) !!}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

@endsection
