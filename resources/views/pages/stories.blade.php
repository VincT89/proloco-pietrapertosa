@extends('layouts.app')

@php
    

    $storie = [
        [
            'id' => 1,
            'title' => (app()->getLocale() === 'en') ? "Memory Space: Interviews in progress" : "Spazio Memoria: Interviste in lavorazione",
            'desc' => (app()->getLocale() === 'en') ? "Section under construction. Real testimonies from the inhabitants will be published." : "Sezione in fase di allestimento. Verranno pubblicate testimonianze reali degli abitanti.",
            'imgs' => [asset('images/torre.jpg'), asset('images/panorama.jpg')]
        ],
        [
            'id' => 2,
            'title' => (app()->getLocale() === 'en') ? "Agricultural Work Archive" : "Archivio Lavori Agricoli",
            'desc' => (app()->getLocale() === 'en') ? "Section under construction." : "Sezione in fase di allestimento.",
            'imgs' => [asset('images/sapori_hero.jpg'), asset('images/percorsi.jpg')]
        ],
        [
            'id' => 3,
            'title' => (app()->getLocale() === 'en') ? "Musical Archive" : "Archivio Musicale",
            'desc' => (app()->getLocale() === 'en') ? "Section under construction." : "Sezione in fase di allestimento.",
            'imgs' => [asset('images/squadra1.jpg'), asset('images/sanGiacomo.jpg')]
        ]
    ];
@endphp

@section('title', ($page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? 'Our Stories' : 'Le Nostre Storie')) . ' · Proloco Pietrapertosana')

@section('content')
    @include('components.section-hero', [
        'title' => $page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? "Our Stories" : "Le Nostre Storie"),
        'subtitle' => $page?->getTranslation('hero_subtitle') ?? ((app()->getLocale() === 'en') ? "Pietrapertosa told by those who live it" : "Pietrapertosa raccontata da chi la vive"),
        'img' => $page?->heroMedia?->optimizedUrl('hero') ?? asset('images/arabata01.jpg')
    ])
    
    @include('components.page-intro', [
        'title' => $page?->getTranslation('intro_title') ?? ((app()->getLocale() === 'en') ? "Community Archive" : "Archivio Comunitario"),
        'text' => $page?->getTranslation('intro_text') ?? ((app()->getLocale() === 'en') ? "Collection of testimonies and local stories to keep the memory of the village alive." : "Raccolta di testimonianze e storie locali per mantenere viva la memoria del paese.")
    ])

    <section class="wrap st-wrap">
        <div class="flex-col">
            @foreach($storie as $i => $storia)
                @php
                    $reverse = $i % 2 !== 0;
                    $hasMultipleImgs = count($storia['imgs']) >= 3;
                @endphp
                <div class="borgo-grid {{ $reverse ? 'rev' : '' }} st-grid">
                    <div class="borgo-imgs">
                        @if($hasMultipleImgs)
                            <div class="bi1 cur" onclick="openGallery(['{{ $storia['imgs'][0] }}', '{{ $storia['imgs'][1] }}', '{{ $storia['imgs'][2] }}'])"><img src="{{ $storia['imgs'][0] }}" alt="{{ $storia['title'] }}" loading="lazy" class="st-img" /></div>
                            <div class="bi2 cur" onclick="openGallery(['{{ $storia['imgs'][0] }}', '{{ $storia['imgs'][1] }}', '{{ $storia['imgs'][2] }}'], 1)"><img src="{{ $storia['imgs'][1] }}" alt="Dettaglio 1" loading="lazy" class="st-img" /></div>
                            <div class="bi3 cur" onclick="openGallery(['{{ $storia['imgs'][0] }}', '{{ $storia['imgs'][1] }}', '{{ $storia['imgs'][2] }}'], 2)"><img src="{{ $storia['imgs'][2] }}" alt="Dettaglio 2" loading="lazy" class="st-img" /></div>
                        @else
                            <div class="bi1 cur st-img-wrap" onclick="openGallery(['{{ $storia['imgs'][0] }}'])">
                                <img src="{{ $storia['imgs'][0] }}" alt="{{ $storia['title'] }}" loading="lazy" class="st-img-full" />
                            </div>
                        @endif
                        <div class="bi-cap fad">{{ $storia['title'] }}</div>
                    </div>
                    <div class="borgo-txt">
                        <h2 class="wr st-title">{{ $storia['title'] }}</h2>
                        <p class="dc fad st-desc">{{ $storia['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    @include('components.page-cta', [
        'subtitle' => (app()->getLocale() === 'en') ? "Myths and memories" : "Miti e memorie",
        'title' => (app()->getLocale() === 'en') ? "Come listen to our stories" : "Vieni ad ascoltare le nostre storie",
        'text' => (app()->getLocale() === 'en') ? "We wait for you in the heart of the Lucanian Dolomites." : "Ti aspettiamo nel cuore delle Dolomiti Lucane.",
        'btnText' => (app()->getLocale() === 'en') ? "Discover Pro Loco" : "Scopri la Pro Loco",
        'btnLink' => url("/" . app()->getLocale() . "/pro-loco"),
        'iconPath' => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>'
    ])

@endsection

