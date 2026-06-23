@extends('layouts.app')

@php
    
@endphp

@section('title', ($page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? 'Territory' : 'Territorio')) . ' · Proloco Pietrapertosana')

@section('content')
    @include('components.section-hero', [
        'title' => $page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? 'Explore the territory' : 'Esplora il Territorio'),
        'subtitle' => $page?->getTranslation('hero_subtitle') ?? ((app()->getLocale() === 'en') ? 'Farms, Artisans and Taste' : 'Aziende Agricole, Artigiani e Sapori'),
        'img' => $page?->heroMedia?->optimizedUrl('hero') ?? asset('images/pietrapertosaTerritorio.png'),
        'bgPosition' => 'center 30%'
    ])
    
    @include('components.page-intro', [
        'title' => $page?->getTranslation('intro_title') ?? ((app()->getLocale() === 'en') ? 'A unique territory' : 'Un territorio unico'),
        'text' => $page?->getTranslation('intro_text') ?? ((app()->getLocale() === 'en') ? 'Discover the excellence of Pietrapertosa.' : 'Scopri le eccellenze di Pietrapertosa.')
    ])

    @if(count($aziende) > 0)
        <section class="wrap terr-section-1">
            <h2 class="terr-title">{{ (app()->getLocale() === 'en') ? "Farms" : "Aziende Agricole" }}</h2>
            <div class="terr-list">
                @foreach($aziende as $index => $az)
                    @php $gallery = $az->galleryMedia->concat($az->externalMedia); @endphp
                    <div class="ed-split terr-item {{ $index % 2 !== 0 ? 'terr-item-rtl' : 'terr-item-ltr' }}">
                        <div class="terr-text-reset">
                            <span class="lbl-mut terr-item-subtitle">{{ $az->getTranslation('subtitle') }}</span>
                            <h3 class="terr-item-title">{{ $az->getTranslation('title') }}</h3>
                            <p class="terr-item-desc">{!! preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', html_entity_decode(strip_tags($az->getTranslation('description')))) !!}</p>
                            @if($az->contact_info)
                                <div class="terr-item-contact">{{ (app()->getLocale() === 'en') ? "Contact:" : "Contatto:" }} {{ $az->contact_info }}</div>
                            @endif
                        </div>
                        @if($gallery->count() > 0)
                            @include('components.interactive-collage', ['images' => $gallery, 'altText' => $az->title])
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if(count($foodtruck) > 0)
        <section class="wrap terr-section-2">
            <h2 class="terr-title">{{ (app()->getLocale() === 'en') ? "Food Truck and Street Food" : "Food Truck e Street Food" }}</h2>
            <div class="terr-list">
                @foreach($foodtruck as $index => $ft)
                    @php $gallery = $ft->galleryMedia->concat($ft->externalMedia); @endphp
                    <div class="ed-split terr-item {{ $index % 2 === 0 ? 'terr-item-rtl' : 'terr-item-ltr' }}">
                        <div class="terr-text-reset">
                            <span class="lbl-mut terr-item-subtitle">{{ $ft->getTranslation('subtitle') }}</span>
                            <h3 class="terr-item-title">{{ $ft->getTranslation('title') }}</h3>
                            <p class="terr-item-desc">{!! preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', html_entity_decode(strip_tags($ft->getTranslation('description')))) !!}</p>
                            @if($ft->contact_info)
                                <div class="terr-item-contact">{{ (app()->getLocale() === 'en') ? "Contact:" : "Contatto:" }} {{ $ft->contact_info }}</div>
                            @endif
                        </div>
                        @if($gallery->count() > 0)
                            @include('components.interactive-collage', ['images' => $gallery, 'altText' => $ft->title])
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if(count($artigiani) > 0)
        <section class="wrap terr-section-3">
            <h2 class="terr-title">{{ (app()->getLocale() === 'en') ? "Artisans" : "Artigiani" }}</h2>
            <div class="terr-list">
                @foreach($artigiani as $index => $art)
                    @php $gallery = $art->galleryMedia->concat($art->externalMedia); @endphp
                    <div class="ed-split terr-item {{ $index % 2 !== 0 ? 'terr-item-rtl' : 'terr-item-ltr' }}">
                        <div class="terr-text-reset">
                            <span class="lbl-mut terr-item-subtitle">{{ $art->getTranslation('subtitle') }}</span>
                            <h3 class="terr-item-title">{{ $art->getTranslation('title') }}</h3>
                            <p class="terr-item-desc">{!! preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', html_entity_decode(strip_tags($art->getTranslation('description')))) !!}</p>
                            @if($art->contact_info)
                                <div class="terr-item-contact">{{ (app()->getLocale() === 'en') ? "Contact:" : "Contatto:" }} {{ $art->contact_info }}</div>
                            @endif
                        </div>
                        @if($gallery->count() > 0)
                            @include('components.interactive-collage', ['images' => $gallery, 'altText' => $art->title])
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif
@endsection
