@extends('layouts.app')
@php
    $title = $item->getTranslation('title');
    $isNews = $kind === 'news';
    $listUrl = route(($isNews ? 'news.' : 'events.').app()->getLocale());
    $backLabel = app()->getLocale() === 'en' ? ($isNews ? 'All news' : 'All events') : ($isNews ? 'Tutte le notizie' : 'Tutti gli eventi');
    $content = $item->getTranslation($isNews ? 'content' : 'description');
    $cover = $kind === 'tradition' ? $item->galleryMedia->firstWhere('type', 'image') : $item->cover;
    $gallery = $item->galleryMedia->concat($item->externalMedia)->unique('id')->reject(fn ($media) => $cover && ($media->id === $cover->id || $media->url === $cover->url))->values();
@endphp
@section('title', ($item->getTranslation('seo_title') ?: $title).' · Proloco Pietrapertosana')
@section('seo_description', $item->getTranslation('seo_description') ?: \Illuminate\Support\Str::limit(strip_tags($content ?? ''), 160))
@section('content')
    <article class="content-detail wrap">
        <header class="detail-header">
            <a class="detail-back" href="{{ $listUrl }}">{{ $backLabel }}</a>
            <h1>{{ $title }}</h1>
            <div class="detail-meta content-meta">
                @if($isNews)
                    <time datetime="{{ ($item->published_at ?? $item->created_at)->toDateString() }}">{{ ($item->published_at ?? $item->created_at)->translatedFormat('j F Y') }}</time>
                @elseif($kind === 'event')
                    <p>@include('components.event-date', ['event' => $item])</p>
                    @if($item->getTranslation('location'))<p>{{ $item->getTranslation('location') }}</p>@endif
                @else
                    <p>{{ $item->getTranslation('subtitle') }}</p>
                @endif
            </div>
        </header>
        <div class="detail-layout {{ $cover ? '' : 'without-cover' }}">
            @if($cover)
                @php($coverUrl = $cover->optimizedUrl('large'))
                <figure class="detail-cover">
                    <button type="button" class="cover-frame" data-gallery='@json([['type' => 'image', 'url' => $coverUrl, 'alt' => $title]])' aria-label="{{ (app()->getLocale() === 'en' ? 'Enlarge image: ' : 'Ingrandisci immagine: ').$title }}">
                        <img class="cover-backdrop" src="{{ $coverUrl }}" alt="" aria-hidden="true" decoding="async">
                        <img class="cover-foreground" src="{{ $coverUrl }}" alt="{{ $title }}" fetchpriority="high" decoding="async">
                    </button>
                    <figcaption>{{ app()->getLocale() === 'en' ? 'Select the image to enlarge it' : 'Seleziona l’immagine per ingrandirla' }}</figcaption>
                </figure>
            @endif
            <div class="detail-reading">
                @if($isNews && $item->getTranslation('excerpt'))
                    <div class="detail-lead">{!! clean($item->getTranslation('excerpt')) !!}</div>
                @endif
                <div class="rich-content">{!! clean($content ?? '') !!}</div>
                @if($isNews && $item->attachmentsMedia->isNotEmpty())
                    <section class="detail-attachments" aria-labelledby="attachments-title">
                        <h2 id="attachments-title">{{ app()->getLocale() === 'en' ? 'Documents' : 'Documenti' }}</h2>
                        <ul>
                            @foreach($item->attachmentsMedia as $attachment)
                                <li><a href="{{ $attachment->url }}" target="_blank" rel="noopener noreferrer">{{ $attachment->alt ?: $attachment->original_name ?: (app()->getLocale() === 'en' ? 'Open document' : 'Apri documento') }} <span>{{ app()->getLocale() === 'en' ? '(opens in a new tab)' : '(si apre in una nuova scheda)' }}</span></a></li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </div>
        </div>
        @if($gallery->isNotEmpty())
            <section class="detail-gallery" aria-labelledby="detail-gallery-title">
                <h2 id="detail-gallery-title">{{ app()->getLocale() === 'en' ? 'Photos and videos' : 'Foto e video' }}</h2>
                @include('components.media-gallery', ['mediaItems' => $gallery, 'galleryTitle' => $title])
            </section>
        @endif
        <div class="detail-end"><a class="detail-back" href="{{ $listUrl }}">{{ $backLabel }}</a></div>
    </article>
@endsection
