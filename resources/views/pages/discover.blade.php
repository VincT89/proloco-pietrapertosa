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
        @include('components.feature-slider', ['items' => $luoghi])
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



@endsection
