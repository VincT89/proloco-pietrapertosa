@extends('layouts.app')

@php
    

    $stats = [
        ['label' => (app()->getLocale() === 'en') ? "Altitude" : "Altitudine", 'value' => "1.088 m"],
        ['label' => (app()->getLocale() === 'en') ? "Inhabitants" : "Abitanti", 'value' => (app()->getLocale() === 'en') ? "Updating" : "In aggiornamento"],
        ['label' => (app()->getLocale() === 'en') ? "Awards" : "Riconoscimenti", 'value' => (app()->getLocale() === 'en') ? "Most Beautiful Villages in Italy" : "I Borghi più belli d'Italia"]
    ];

    $highlights = [
        [
            'id' => 'arabata',
            'title' => (app()->getLocale() === 'en') ? "The Arabata" : "L'Arabata",
            'subtitle' => (app()->getLocale() === 'en') ? "Find out more" : "Scopri di più",
            'desc' => (app()->getLocale() === 'en') ? "The ancient district founded by the Saracen rulers is the beating and most rugged heart of Pietrapertosa. A labyrinth of narrow alleys and houses wedged into one another." : "L'antico quartiere fondato dai dominatori Saraceni è il cuore pulsante e più impervio di Pietrapertosa. Un labirinto di viuzze strettissime e case incastrate le une sulle altre.",
            'img' => asset('images/arabata.jpeg')
        ],
        [
            'id' => 'castello',
            'title' => (app()->getLocale() === 'en') ? "The Saracen Castle" : "Il Castello Saraceno",
            'subtitle' => (app()->getLocale() === 'en') ? "Find out more" : "Scopri di più",
            'desc' => (app()->getLocale() === 'en') ? "Ancient fortress originally Norman-Swabian, built in an impregnable strategic point dominating the entire valley of the Basento river." : "Antica fortezza originariamente normanno-sveva, costruita in un punto strategico inespugnabile che domina l'intera valle del fiume Basento.",
            'img' => asset('images/castello.jpg')
        ],
        [
            'id' => 'sette-pietre',
            'title' => (app()->getLocale() === 'en') ? "The Seven Stones" : "Le Sette Pietre",
            'subtitle' => (app()->getLocale() === 'en') ? "Find out more" : "Scopri di più",
            'desc' => (app()->getLocale() === 'en') ? "Path that connects Pietrapertosa to Castelmezzano winding along the Caperrino valley in seven stages." : "Sentiero che collega Pietrapertosa a Castelmezzano snodandosi lungo la valle di Caperrino in sette tappe.",
            'img' => asset('images/percorsi.jpg')
        ]
    ];
@endphp

@section('title', ($page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? 'The Village of Pietrapertosa' : 'Il Borgo di Pietrapertosa')) . ' · Proloco Pietrapertosana')

@section('content')
    @include('components.section-hero', [
        'title' => $page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? "The Village of Pietrapertosa" : "Il Borgo di Pietrapertosa"),
        'subtitle' => $page?->getTranslation('hero_subtitle') ?? ((app()->getLocale() === 'en') ? "A village suspended between sky and rock" : "Un paese sospeso tra cielo e roccia"),
        'img' => $page?->heroMedia?->optimizedUrl('hero') ?? asset('images/immaginePaese.jpg')
    ])
    
    <div class="wrap borgo-stats-wrap">
        <div class="borgo-stats-grid">
            @foreach($stats as $s)
                <div class="fad parchment-badge borgo-stat-badge">
                    <div class="borgo-stat-border"></div>
                    <div class="borgo-stat-val">{{ $s['value'] }}</div>
                    <div class="borgo-stat-lbl">{{ $s['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    @include('components.page-intro', [
        'title' => $page?->getTranslation('intro_title') ?? ((app()->getLocale() === 'en') ? "A dive into the past" : "Un tuffo nel passato"),
        'text' => $page?->getTranslation('intro_text') ?? ((app()->getLocale() === 'en') ? "Nestled in the Lucanian Dolomites, at 1,088 meters above sea level, Pietrapertosa is the highest municipality in Basilicata. Its origins are lost in the mists of time, amidst ancient rock civilizations and Byzantine, Arab, and Norman dominations. From the Saracen fortifications to the doors of the houses wedged into the living rock, every corner tells of a proud people and secular traditions guarded by the mountains." : "Incastonato nelle Dolomiti Lucane, a 1.088 metri d'altezza, Pietrapertosa è il comune più alto della Basilicata. Le sue origini si perdono nella notte dei tempi, tra antiche civiltà rupestri e dominazioni bizantine, arabe e normanne. Dalle fortificazioni saracene fino alle porte delle case incastrate nella pietra viva, ogni angolo racconta di un popolo fiero e di tradizioni secolari custodite dalle montagne.")
    ])

    <section class="wrap pb-30">
        <div class="flex-col">
            @foreach($highlights as $i => $h)
                @include('components.experience-card', [
                    'reverse' => $i % 2 !== 0,
                    'img' => $h['img'],
                    'title' => $h['title'],
                    'subtitle' => $h['subtitle'],
                    'desc' => $h['desc']
                ])
            @endforeach
        </div>
    </section>
@endsection
