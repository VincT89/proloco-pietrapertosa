@extends('layouts.app')

@php
    
@endphp

@section('title', ($page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? 'Excellences' : 'Eccellenze')) . ' · Proloco Pietrapertosana')

@section('content')
    @include('components.section-hero', [
        'title' => $page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? 'Explore our excellences' : 'Esplora le Eccellenze'),
        'subtitle' => $page?->getTranslation('hero_subtitle') ?? ((app()->getLocale() === 'en') ? 'Farms, Artisans and Taste' : 'Aziende Agricole, Artigiani e Sapori'),
        'img' => $page?->heroMedia?->optimizedUrl('hero') ?? asset('images/pietrapertosaEccellenze.jpg'),
        'bgPosition' => 'center 30%'
    ])
    
    @include('components.page-intro', [
        'title' => $page?->getTranslation('intro_title') ?? ((app()->getLocale() === 'en') ? 'Tradition, craftsmanship and authentic flavors' : 'Tradizione, artigianato e sapori autentici'),
        'text' => $page?->getTranslation('intro_text') ?? ((app()->getLocale() === 'en') ? 'Discover the excellence of Pietrapertosa.' : 'Scopri le eccellenze di Pietrapertosa.')
    ])

    @if(count($aziende) > 0)
        <section class="wrap terr-section-1">

            @include('components.feature-slider', ['items' => $aziende])
        </section>
    @endif

    @if(count($foodtruck) > 0)
        <section class="wrap terr-section-2">

            @include('components.feature-slider', ['items' => $foodtruck])
        </section>
    @endif

    @if(count($artigiani) > 0)
        <section class="wrap terr-section-3">

            @include('components.feature-slider', ['items' => $artigiani])
        </section>
    @endif
@endsection

