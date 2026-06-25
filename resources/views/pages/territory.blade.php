@extends('layouts.app')

@php
    
@endphp

@section('title', ($page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? 'Territory' : 'Territorio')) . ' · Proloco Pietrapertosana')

@section('content')
    @include('components.section-hero', [
        'title' => $page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? 'Explore the territory' : 'Esplora il Territorio'),
        'subtitle' => $page?->getTranslation('hero_subtitle') ?? ((app()->getLocale() === 'en') ? 'Farms, Artisans and Taste' : 'Aziende Agricole, Artigiani e Sapori'),
        'img' => $page?->heroMedia?->optimizedUrl('hero') ?? asset('images/pietrapertosaTerritorio.jpg'),
        'bgPosition' => 'center 30%'
    ])
    
    @include('components.page-intro', [
        'title' => $page?->getTranslation('intro_title') ?? ((app()->getLocale() === 'en') ? 'A unique territory' : 'Un territorio unico'),
        'text' => $page?->getTranslation('intro_text') ?? ((app()->getLocale() === 'en') ? 'Discover the excellence of Pietrapertosa.' : 'Scopri le eccellenze di Pietrapertosa.')
    ])

    @if(count($aziende) > 0)
        <section class="wrap terr-section-1">
            <h2 class="terr-title">{{ (app()->getLocale() === 'en') ? "Farms" : "Aziende Agricole" }}</h2>
            @include('components.feature-slider', ['items' => $aziende])
        </section>
    @endif

    @if(count($foodtruck) > 0)
        <section class="wrap terr-section-2">
            <h2 class="terr-title">{{ (app()->getLocale() === 'en') ? "Food Truck and Street Food" : "Food Truck e Street Food" }}</h2>
            @include('components.feature-slider', ['items' => $foodtruck])
        </section>
    @endif

    @if(count($artigiani) > 0)
        <section class="wrap terr-section-3">
            <h2 class="terr-title">{{ (app()->getLocale() === 'en') ? "Artisans" : "Artigiani" }}</h2>
            @include('components.feature-slider', ['items' => $artigiani])
        </section>
    @endif
@endsection

