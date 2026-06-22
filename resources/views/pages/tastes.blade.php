@extends('layouts.app')

@php
    
@endphp

@section('title', ($page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? 'Tastes' : 'Sapori')) . ' · Proloco Pietrapertosana')

@section('content')
    @include('components.section-hero', [
        'title' => $page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? 'Traditional Tastes' : 'I Sapori della Tradizione'),
        'subtitle' => $page?->getTranslation('hero_subtitle') ?? ((app()->getLocale() === 'en') ? 'Pietrapertosa at the table' : 'Pietrapertosa a tavola'),
        'img' => $page?->heroMedia?->url ?? asset('images/sapori_hero.png')
    ])
    
    @include('components.page-intro', [
        'title' => $page?->getTranslation('intro_title') ?? ((app()->getLocale() === 'en') ? 'A culinary journey' : 'Un viaggio culinario'),
        'text' => $page?->getTranslation('intro_text') ?? ((app()->getLocale() === 'en') ? 'Discover the typical dishes of our mountains.' : 'Scopri i piatti tipici delle nostre montagne.')
    ])

    <section class="wrap sapori-section">
        <div class="sapori-list">
            @foreach($piatti ?? [] as $i => $sapore)
                @php
                    $gallery = $sapore->galleryMedia->pluck('url')->toArray();
                    $img = count($gallery) > 0 ? $gallery[0] : asset('images/pietrapertosaBacheca.png');
                @endphp
                @include('components.experience-card', [
                    'reverse' => $i % 2 !== 0,
                    'img' => $img,
                    'title' => $sapore->getTranslation('title'),
                    'subtitle' => $sapore->getTranslation('subtitle'),
                    'desc' => strip_tags($sapore->getTranslation('description')),
                    'stats' => is_string($sapore->stats) ? json_decode($sapore->stats, true) : ($sapore->stats ?: [])
                ])
            @endforeach
        </div>
    </section>

    @include('components.page-cta', [
        'subtitle' => (app()->getLocale() === 'en') ? "Tradition at the table" : "Tradizione a tavola",
        'title' => (app()->getLocale() === 'en') ? "Come and taste" : "Vieni ad assaggiare",
        'text' => (app()->getLocale() === 'en') ? "Discover local restaurants and farms in the area." : "Scopri i ristoranti locali e le aziende agricole del territorio.",
        'btnText' => (app()->getLocale() === 'en') ? "Contact us" : "Contattaci",
        'btnLink' => url("/" . app()->getLocale() . "/contatti"),
        'bgImage' => asset('images/sfondo_cta.jpeg')
    ])
@endsection
