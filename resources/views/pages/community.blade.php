@extends('layouts.app')

@php
    
@endphp

@section('title', ($page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? 'Community' : 'Comunità')) . ' · Proloco Pietrapertosana')

@section('content')
    @include('components.section-hero', [
        'title' => $page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? 'The Community in the Foreground' : 'La Comunità in Primo Piano'),
        'subtitle' => $page?->getTranslation('hero_subtitle') ?? ((app()->getLocale() === 'en') ? 'Associations and Local Groups' : 'Associazioni e Gruppi Locali'),
        'img' => $page?->heroMedia?->optimizedUrl('hero') ?? asset('images/pietrapertosaComunita.jpg')
    ])
    
    @include('components.page-intro', [
        'title' => $page?->getTranslation('intro_title') ?? ((app()->getLocale() === 'en') ? 'Discover our reality' : 'Scopri la nostra realtà'),
        'text' => $page?->getTranslation('intro_text') ?? ((app()->getLocale() === 'en') ? 'The associations that keep the village alive.' : 'Le associazioni che mantengono vivo il paese.')
    ])

    <section class="cm-sec wrap pb-80">
        @foreach($realta as $asso)
            @php
                $gallery = $asso->galleryMedia->map(fn($m) => $m->optimizedUrl('large'))->toArray();
                $thumbs = $asso->galleryMedia->map(fn($m) => $m->optimizedUrl('card'))->toArray();
            @endphp
            <div class="sticky-split">
                <div class="sticky-text">
                    <span class="cm-subtitle">
                        {{ $asso->getTranslation('subtitle') }}
                    </span>
                    <h3 class="cm-title">
                        {{ $asso->getTranslation('title') }}
                    </h3>
                    <div class="cm-desc">{!! clean($asso->getTranslation('description')) !!}</div>
                    @if($asso->contact_info)
                        <div class="cm-contact">{{ (app()->getLocale() === 'en') ? "Contact:" : "Contatto:" }} {{ $asso->contact_info }}</div>
                    @endif
                </div>

                <div class="sticky-imgs">
                    @if(count($gallery) > 0)
                        @foreach($gallery as $i => $img)
                            <div class="sticky-img-box cur" onclick='openGallery(@json($gallery), {{ $i }})'>
                                <img src="{{ $thumbs[$i] }}" alt="{{ $asso->title }} {{ $i + 1 }}" class="cm-img" loading="lazy" decoding="async" />
                                <div class="cm-img-overlay"></div>
                            </div>
                        @endforeach
                    @else
                        <div class="sticky-img-box cm-img-empty">
                            <span class="cm-empty-text">{{ (app()->getLocale() === 'en') ? "No image" : "Nessuna immagine" }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </section>
@endsection
