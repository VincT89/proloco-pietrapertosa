@extends('layouts.app')

@section('title', 'Proloco Pietrapertosana')

@php
    

    // Dati fallback presi dal React data/home.js in caso DB vuoto
    if ($events->isEmpty()) {
        $fallbackEvents = [
            (object)['id' => 1, 'title' => 'Sapori d\'Autunno', 'title_en' => 'Tastes of Autumn', 'cover_url' => asset('images/sapori_hero.png'), 'start_date' => null, 'fallback_date' => (app()->getLocale() === 'en') ? 'November' : 'Novembre'],
            (object)['id' => 2, 'title' => 'Sulle Tracce degli Arabi', 'title_en' => 'On the Traces of Arabs', 'cover_url' => asset('images/arabata.jpeg'), 'start_date' => null, 'fallback_date' => (app()->getLocale() === 'en') ? 'August' : 'Agosto'],
            (object)['id' => 3, 'title' => 'Borgo Festival', 'title_en' => 'Village Festival', 'cover_url' => asset('images/PietrapertosaEventi.png'), 'start_date' => null, 'fallback_date' => (app()->getLocale() === 'en') ? 'July' : 'Luglio']
        ];
        $eventsToDisplay = $fallbackEvents;
    } else {
        $eventsToDisplay = $events->map(function($ev) {
            return (object)[
                'id' => $ev->id,
                'title' => $ev->title,
                'title_en' => $ev->title_en,
                'cover_url' => $ev->cover ? $ev->cover->optimizedUrl('card') : null,
                'start_date' => $ev->start_date,
                'fallback_date' => null
            ];
        });
    }

    $scopriData = $page?->data['discover_items'] ?? null;
    if ($scopriData && is_array($scopriData) && count($scopriData) > 0) {
        $fallbackScopri = collect($scopriData)->map(function ($item, $key) {
            return (object)[
                'id' => $key + 1,
                'nome' => $item['nome'] ?? '',
                'nome_en' => $item['nome_en'] ?? '',
                'img' => $item['img'] ?? ''
            ];
        })->toArray();
    } else {
        $fallbackScopri = [
            (object)['id' => 1, 'nome' => 'L\'Arabata', 'nome_en' => 'The Arabata', 'img' => asset('images/arabata01.jpg')],
            (object)['id' => 2, 'nome' => 'Castello Normanno-Svevo', 'nome_en' => 'Norman-Swabian Castle', 'img' => asset('images/castello.jpg')],
            (object)['id' => 3, 'nome' => 'Sentiero delle Sette Pietre', 'nome_en' => 'Path of the Seven Stones', 'img' => asset('images/percorsi.jpg')],
            (object)['id' => 4, 'nome' => 'Volo dell\'Angelo', 'nome_en' => 'Flight of the Angel', 'img' => asset('images/voloAngelo.jpg')]
        ];
    }
@endphp

@section('content')
    <header class="hero" id="top">
        <div class="hero-media" id="heroMedia">
            <div class="h-bg">
                <img src="{{ $page?->heroMedia?->optimizedUrl('hero') ?? asset('images/immaginePaese.jpg') }}" alt="" class="home-hero-bg" />
                <div class="home-hero-gradient" style="background: rgba(0,0,0,{{ $page?->hero_overlay_opacity ?? 0.4 }});"></div>
            </div>
        </div>
        <div class="hero-in">
            <h1 class="hero-h1">
                <span class="ln">
                    <span>{{ $page?->getTranslation('hero_title') ?? __('home.hero_title_1') }}</span>
                </span>
                <span class="ln">
                    <span class="gold">{!! clean($page?->getTranslation('hero_subtitle') ?? __('home.hero_title_2')) !!}</span>
                </span>
            </h1>
            <div class="hero-foot">
                @php
                    $introHtml = $page?->getTranslation('intro_text');
                    if (empty(trim(strip_tags($introHtml)))) {
                        $introHtml = null;
                    }
                @endphp
                @if($introHtml)
                    <div class="hero-desc">{!! clean($introHtml) !!}</div>
                @else
                    <p class="hero-desc">{{ __('home.hero_desc') }}</p>
                @endif
                @if($page?->getTranslation('hero_cta_text') && $page?->hero_cta_url)
                    <div style="margin-top: 30px;">
                        <a href="{{ $page->hero_cta_url }}" class="ed-btn ed-btn-gold" style="font-size: 1.1rem; padding: 12px 30px;">{{ $page->getTranslation('hero_cta_text') }}</a>
                    </div>
                @endif
            </div>
        </div>
        <div class="hero-scroll"><i></i></div>
    </header>

    @if(count($eventsToDisplay) > 0)
        <section class="ed-sec">
            <div class="ed-wrap">
                <div class="ed-section-header">
                    <div>
                        <span class="ed-subtitle">@lang('home.calendar')</span>
                        <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'events' : 'eventi')) }}" class="ed-link-clean">
                            <h2 class="ed-title ed-title-no-margin">{{ !empty($page?->data['events_title' . (app()->getLocale() === 'en' ? '_en' : '')]) ? $page->data['events_title' . (app()->getLocale() === 'en' ? '_en' : '')] : __('home.upcoming_events') }}</h2>
                        </a>
                    </div>
                    <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'events' : 'eventi')) }}" class="ed-link-more">@lang('home.see_all')</a>
                </div>
                
                <div class="ed-grid">
                    @foreach($eventsToDisplay as $ev)
                        <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'events' : 'eventi')) }}" class="ed-link-clean">
                            <div class="ed-card">
                                <div class="ed-img-box tall">
                                    <img src="{{ $ev->cover_url ?? 'https://placehold.co/400x600/14181f/d9aa63?text=Locandina' }}" alt="{{ app()->getLocale() === 'en' && !empty($ev->title_en) ? $ev->title_en : $ev->title }}" />
                                </div>
                                <div class="ed-glass-cap">
                                    <span>
                                        @if($ev->start_date)
                                            {{ \Carbon\Carbon::parse($ev->start_date)->format((app()->getLocale() === 'en') ? 'M d, Y' : 'd/m/Y') }}
                                        @else
                                            {{ $ev->fallback_date }}
                                        @endif
                                    </span>
                                    <h3 class="ed-title">{{ app()->getLocale() === 'en' && !empty($ev->title_en) ? $ev->title_en : $ev->title }}</h3>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="ed-sec alt">
        <div class="ed-wrap">
            <div class="ed-section-header">
                <div>
                    <span class="ed-subtitle">@lang('home.featured_updates')</span>
                    <h2 class="ed-title ed-title-no-margin">{{ !empty($page?->data['news_title' . (app()->getLocale() === 'en' ? '_en' : '')]) ? $page->data['news_title' . (app()->getLocale() === 'en' ? '_en' : '')] : __('home.latest_news') }}</h2>
                </div>
            </div>
            
            <div class="ed-list ed-list-wrapper">
                @if($news->isEmpty())
                    <div class="ed-empty-msg">
                        @lang('home.no_news')
                    </div>
                @else
                    @foreach($news as $notizia)
                        <a href="{{ url("/" . app()->getLocale() . "/" . ((app()->getLocale() === 'en') ? 'news' : 'notizie')) }}" class="ed-link-clean">
                            <div class="ed-list-item ed-list-item-hover">
                                <div class="ed-item-row">
                                    <div class="ed-item-date">
                                        {{ \Carbon\Carbon::parse($notizia->published_at ?? $notizia->created_at)->format((app()->getLocale() === 'en') ? 'M d, Y' : 'd/m/Y') }}
                                    </div>
                                    <div>
                                        <h4 class="ed-item-title">
                                            {{ $notizia->getTranslation('title') }}
                                        </h4>
                                        <p class="ed-item-category">
                                            {{ (app()->getLocale() === 'en') ? 'News' : 'Avviso' }}
                                        </p>
                                    </div>
                                </div>
                                <span class="ed-item-arrow">→</span>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <section class="ed-sec alt">
        <div class="ed-wrap">
            <div class="ed-split ed-split-center">
                <div class="ed-col-center">
                    <span class="ed-subtitle">{{ !empty($page?->data['discover_subtitle' . (app()->getLocale() === 'en' ? '_en' : '')]) ? $page->data['discover_subtitle' . (app()->getLocale() === 'en' ? '_en' : '')] : __('home.explore_territory') }}</span>
                    <h2 class="ed-title ed-title-large">{{ !empty($page?->data['discover_title' . (app()->getLocale() === 'en' ? '_en' : '')]) ? $page->data['discover_title' . (app()->getLocale() === 'en' ? '_en' : '')] : __('home.discover_pietrapertosa') }}</h2>
                    <p class="ed-desc-text">{{ !empty($page?->data['discover_text' . (app()->getLocale() === 'en' ? '_en' : '')]) ? $page->data['discover_text' . (app()->getLocale() === 'en' ? '_en' : '')] : __('home.discover_desc') }}</p>
                    
                    <ul class="ed-ul-clean">
                        @foreach($fallbackScopri as $scopri)
                            <li class="ed-li-flex">
                                <span class="ed-list-bullet"></span>
                                <span class="ed-li-text">{{ app()->getLocale() === 'en' && !empty($scopri->nome_en) ? $scopri->nome_en : $scopri->nome }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div>
                        <a href="https://www.borgoracconta.it/citta/pietrapertosa/" target="_blank" rel="noopener noreferrer" class="ed-btn ed-btn-gold">
                            @lang('home.go_to_borgo')
                        </a>
                    </div>
                </div>

                <div class="borgo-imgs">
                    @if(isset($fallbackScopri[0]))
                        <div class="bi1"><img src="{{ $fallbackScopri[0]->img ?? '' }}" alt="{{ $fallbackScopri[0]->nome ?? '' }}" /></div>
                    @endif
                    @if(isset($fallbackScopri[1]))
                        <div class="bi2"><img src="{{ $fallbackScopri[1]->img ?? '' }}" alt="{{ $fallbackScopri[1]->nome ?? '' }}" /></div>
                    @endif
                    @if(isset($fallbackScopri[2]))
                        <div class="bi3"><img src="{{ $fallbackScopri[2]->img ?? '' }}" alt="{{ $fallbackScopri[2]->nome ?? '' }}" /></div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                document.body.classList.add('ready');
            }, 100);
        });
    </script>
@endsection
