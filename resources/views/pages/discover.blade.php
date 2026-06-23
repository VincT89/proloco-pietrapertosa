@extends('layouts.app')

@php
    

    $luoghi = [
        ['id' => 1, 'nome' => (app()->getLocale() === 'en') ? "The Arabata" : "L'Arabata", 'img' => asset('images/arabata01.jpg')],
        ['id' => 2, 'nome' => (app()->getLocale() === 'en') ? "The Norman-Swabian Castle" : "Il Castello Normanno-Svevo", 'img' => asset('images/castello.jpg')],
        ['id' => 3, 'nome' => (app()->getLocale() === 'en') ? "The Path of the Seven Stones" : "Il Sentiero delle Sette Pietre", 'img' => asset('images/percorsi.jpg')],
        ['id' => 4, 'nome' => (app()->getLocale() === 'en') ? "Flight of the Angel" : "Il Volo dell'Angelo", 'img' => asset('images/voloAngelo.jpg')]
    ];

    $servizi = [
        ['id' => 1, 'nome' => "Infopoint I.A.T. / Pro Loco", 'info' => (app()->getLocale() === 'en') ? "Tourist Information and Reception" : "Informazioni Turistiche e Accoglienza", 'contatto' => (app()->getLocale() === 'en') ? "Tel: +39 0971 983002 | Mobile: +39 342 989 6770" : "Tel: 0971 983002 | Cell: 342 989 6770"],
        ['id' => 2, 'nome' => (app()->getLocale() === 'en') ? "Municipality of Pietrapertosa" : "Comune di Pietrapertosa", 'info' => (app()->getLocale() === 'en') ? "Switchboard and Municipal Offices" : "Centralino e Uffici Comunali", 'contatto' => (app()->getLocale() === 'en') ? "Tel: +39 0971 983052" : "Tel: 0971 983052"],
        ['id' => 3, 'nome' => (app()->getLocale() === 'en') ? "Flight of the Angel" : "Volo dell'Angelo", 'info' => (app()->getLocale() === 'en') ? "Ticket Office and Facility Information" : "Biglietteria e Informazioni Impianti", 'contatto' => (app()->getLocale() === 'en') ? "Tel: +39 0971 983110 | Mobile: +39 342 989 6770" : "Tel: 0971 983110 | Cell: 342 989 6770"]
    ];
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
        <div class="scopri-grid-1">
            @foreach($luoghi as $luogo)
                <div class="scopri-card">
                    <div class="scopri-img-wrap">
                        <img src="{{ $luogo['img'] }}" alt="{{ $luogo['nome'] }}" class="scopri-img" />
                    </div>
                    <div class="scopri-card-overlay">
                        <h3 class="scopri-card-title">{{ $luogo['nome'] }}</h3>
                    </div>
                </div>
            @endforeach
        </div>

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
                    <h3 class="scopri-info-card-title">{{ $item['nome'] }}</h3>
                    <p class="scopri-info-card-text">{{ $item['info'] }}</p>
                    <div class="scopri-info-card-contact">{{ $item['contatto'] }}</div>
                </div>
            @endforeach
        </div>
    </section>

@endsection
