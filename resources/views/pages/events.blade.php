@extends('layouts.app')

@section('title', ($page?->getTranslation('hero_title') ?? __('events.hero_title')) . ' · Proloco Pietrapertosana')

@section('content')
    @include('components.section-hero', [
        'title' => $page?->getTranslation('hero_title') ?? __('events.hero_title'),
        'subtitle' => $page?->getTranslation('hero_subtitle') ?? __('events.hero_subtitle'),
        'img' => $page?->heroMedia?->optimizedUrl('hero') ?? asset('images/PietrapertosaEventi.jpeg')
    ])
    
    <!-- Eventi Annuali -->
    <section class="ev-sec-annuali">
        <div class="ed-wrap ev-header-wrap">
            <span class="ed-subtitle">@lang('events.annual_subtitle')</span>
            <h2 class="ed-title ev-title-nomargin">@lang('events.annual_title')</h2>
        </div>

        <div class="ev-card-grid">
            @foreach($annualEvents as $ev)
                @php
                    $galleryLarge = $ev->galleryMedia->map(fn($m) => [
                        'type' => $m->type, 
                        'provider' => $m->provider, 
                        'url' => $m->type === 'image' ? $m->optimizedUrl('large') : ($m->type === 'video' ? $m->optimizedVideoUrl() : $m->url), 
                        'embed_url' => $m->embed_url
                    ])->toArray();
                    $galleryThumb = $ev->galleryMedia->map(fn($m) => $m->optimizedUrl('card'))->toArray();
                @endphp
                <div class="ev-card-giant {{ count($galleryThumb) > 0 ? 'is-clickable' : '' }}" @if(count($galleryLarge) > 0) onclick='openGallery(@json($galleryLarge))' @endif>
                    <div class="ev-card-bg">
                        @if(count($galleryThumb) > 0)
                            @include('components.auto-carousel', ['images' => $galleryThumb, 'interval' => 3000 + $loop->index * 500])
                        @else
                            <div class="ev-card-placeholder"></div>
                        @endif
                    </div>
                    <div class="ev-card-overlay-right"></div>
                    <div class="ev-card-content">
                        <div class="ev-card-content-inner">
                            <span class="ev-card-subtitle">
                                {{ $ev->getTranslation('subtitle') }}
                            </span>
                            <h3 class="ev-card-title">
                                {{ $ev->getTranslation('title') }}
                            </h3>
                            <p class="ev-card-desc">{{ Str::limit(strip_tags(html_entity_decode($ev->getTranslation('description'))), 150) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Eventi Dinamici -->
    <section class="ev-sec-current">
        <div class="ed-wrap ev-header-wrap">
            <span class="ed-subtitle">@lang('events.upcoming_subtitle')</span>
            <h2 class="ed-title ev-title-nomargin">{{ __('events.upcoming_title', ['year' => date('Y')]) }}</h2>
        </div>
        
        @if($events->isEmpty())
            <div class="ev-empty-msg-2">
                <p class="ev-empty-italic">@lang('events.no_events_upcoming')</p>
            </div>
        @else
            <div class="ev-grid-cards">
                @foreach($events as $ev)
                    @php
                    $galleryLarge = $ev->galleryMedia->map(fn($m) => [
                        'type' => $m->type, 
                        'provider' => $m->provider, 
                        'url' => $m->type === 'image' ? $m->optimizedUrl('large') : ($m->type === 'video' ? $m->optimizedVideoUrl() : $m->url), 
                        'embed_url' => $m->embed_url
                    ])->toArray();
                        $galleryThumb = $ev->galleryMedia->map(fn($m) => $m->optimizedUrl('card'))->toArray();
                    @endphp
                    <div class="ev-card-normal {{ count($galleryThumb) > 0 ? 'is-clickable' : '' }}" @if(count($galleryLarge) > 0) onclick='openGallery(@json($galleryLarge))' @endif>
                        <div class="ev-card-normal-bg">
                            @if($ev->cover)
                                <img src="{{ $ev->cover->optimizedUrl('card') }}" class="pos-abs-cover object-contain" loading="lazy" decoding="async" />
                            @elseif(count($galleryThumb) > 0)
                                @include('components.auto-carousel', ['images' => $galleryThumb, 'interval' => 3000 + $loop->index * 500, 'objectFit' => 'contain'])
                            @else
                                <div class="ev-card-placeholder"></div>
                            @endif
                        </div>
                        <div class="ev-card-normal-overlay"></div>
                        <div class="ev-card-normal-content">
                            <div class="ev-card-normal-inner">
                                <h3 class="ev-card-title">
                                    {{ $ev->getTranslation('title') }}
                                </h3>
                                <span class="ev-card-normal-subtitle">
                                    @if($ev->start_date)
                                        {{ \Carbon\Carbon::parse($ev->start_date)->format((app()->getLocale() === 'en') ? 'M d, Y' : 'd/m/Y') }}
                                        @if($ev->end_date && $ev->end_date != $ev->start_date)
                                            - {{ \Carbon\Carbon::parse($ev->end_date)->format((app()->getLocale() === 'en') ? 'M d, Y' : 'd/m/Y') }}
                                        @endif
                                    @else
                                        {{ (app()->getLocale() === 'en') ? 'To be defined' : 'Da definire' }}
                                    @endif
                                </span>
                                <div class="event-desc ev-card-normal-desc">
                                    {!! clean($ev->getTranslation('description') ?? '') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- Calendario e Archivio -->
    <section class="ed-sec alt">
        <div class="ed-wrap">
            <div class="ev-cal-wrap">
                <div>
                    <span class="ed-subtitle">@lang('events.calendar_subtitle')</span>
                    <h2 class="ed-title ev-cal-title">@lang('events.calendar_title')</h2>
                    @if($events->isEmpty())
                        <p class="ev-empty-msg-2 ev-empty-italic">@lang('events.no_events_calendar')</p>
                    @else
                        <div class="ev-cal-table-wrap">
                            <table class="resp-table ev-cal-table">
                                <thead>
                                    <tr class="ev-cal-th-row">
                                        <th class="ev-cal-th-1">@lang('events.table_date')</th>
                                        <th class="ev-cal-th-2">@lang('events.table_title')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $cal)
                                        @php
                                            $galleryList = $cal->galleryMedia->pluck('url')->toArray();
                                            $allGalleryMedia = $cal->galleryMedia->concat($cal->externalMedia);
                                            $galleryJsonData = $allGalleryMedia->map(fn($m) => [
                                                'type' => $m->type,
                                                'provider' => $m->provider,
                                                'url' => $m->type === 'image' ? $m->optimizedUrl('large') : ($m->type === 'video' ? $m->optimizedVideoUrl() : $m->url),
                                                'embed_url' => $m->embed_url
                                            ]);
                                        @endphp
                                        <tr class="ev-cal-tr {{ count($galleryList) > 0 || $allGalleryMedia->count() > 0 ? 'is-clickable' : '' }}" @if(count($galleryList) > 0 || $allGalleryMedia->count() > 0) onclick='openGallery(@json($galleryJsonData))' @endif>
                                            <td class="ev-cal-td-1">
                                                <div class="ev-cal-date">
                                                    @if($cal->start_date)
                                                        {{ \Carbon\Carbon::parse($cal->start_date)->format((app()->getLocale() === 'en') ? 'M d, Y' : 'd/m/Y') }}
                                                    @else
                                                        {{ (app()->getLocale() === 'en') ? 'To be defined' : 'Da definire' }}
                                                    @endif
                                                </div>
                                                @if($cal->location)
                                                    <div class="ev-cal-loc">{{ $cal->getTranslation('location') }}</div>
                                                @endif
                                            </td>
                                            <td class="ev-cal-td-2">
                                                <h4 class="ev-cal-item-title">{{ $cal->getTranslation('title') }}</h4>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="margin-top: 40px;">
                            {{ $events->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>



@endsection
