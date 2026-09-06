@extends('layouts.app')

@section('title', 'Proloco Pietrapertosana')

@php
    $scopriData = $page?->data['discover_items'] ?? null;
    $fallbackScopri = [];
    if ($scopriData && is_array($scopriData) && count($scopriData) > 0) {
        $fallbackScopri = collect($scopriData)->map(function ($item, $key) {
            $imgUrl = '';
            if (!empty($item['img_media_id'])) {
                $media = \App\Models\Media::find($item['img_media_id']);
                if ($media) $imgUrl = $media->optimizedUrl('card');
            } else if (!empty($item['img'])) {
                $imgUrl = $item['img'];
            }
            
            return (object)[
                'id' => $key + 1,
                'nome' => $item['nome'] ?? '',
                'nome_en' => $item['nome_en'] ?? '',
                'img' => asset($imgUrl)
            ];
        })->toArray();
    }
@endphp

@section('content')
    <header class="hero" id="top">
        <div class="hero-media" id="heroMedia">
            <div class="h-bg">
                @if($page?->heroMedia)
                    <img src="{{ $page->heroMedia->optimizedUrl('hero') }}" alt="" class="home-hero-bg" />
                @endif
                <div class="home-hero-gradient" style="background: rgba(0,0,0,{{ $page?->hero_overlay_opacity ?? 0.4 }});"></div>
            </div>
        </div>
        <div class="hero-in">
            <h1 class="hero-h1">
                <span class="ln">
                    <span>{!! clean($page?->getTranslation('hero_title') ?? __('home.hero_title_1')) !!}</span>
                </span>
                <span class="ln">
                    <span>{!! clean($page?->getTranslation('hero_subtitle') ?? __('home.hero_title_2')) !!}</span>
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
                    <div class="hero-action">
                        <a href="{{ $page->hero_cta_url }}" class="ed-btn ed-btn-gold">{{ $page->getTranslation('hero_cta_text') }}</a>
                    </div>
                @endif
            </div>
        </div>
        <div class="hero-scroll"><i></i></div>
    </header>

    @if(count($events) > 0)
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
                
                <div class="content-grid">
                    @foreach($events as $item)
                        @include('components.content-card', ['kind' => 'event'])
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
            
            <div class="content-grid">
                @forelse($news as $item)
                    @include('components.content-card', ['kind' => 'news'])
                @empty
                    <p>@lang('home.no_news')</p>
                @endforelse
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
                        <li class="ed-li-flex">
                            <span class="ed-list-bullet"></span>
                            <span class="ed-li-text">{{ app()->getLocale() === 'en' ? '...and much more...' : '...e molto altro...' }}</span>
                        </li>
                    </ul>

                    @php
                        $discoverCtaUrl = $page?->data['discover_cta_url'] ?? 'https://www.borgoracconta.it/citta/pietrapertosa/';
                        $discoverCtaText = !empty($page?->data['discover_cta_text' . (app()->getLocale() === 'en' ? '_en' : '')]) 
                            ? $page->data['discover_cta_text' . (app()->getLocale() === 'en' ? '_en' : '')] 
                            : __('home.go_to_borgo');
                    @endphp
                    @if($discoverCtaUrl)
                    <div>
                        <a href="{{ $discoverCtaUrl }}" target="_blank" rel="noopener noreferrer" class="ed-btn ed-btn-gold">
                            {{ $discoverCtaText }}
                        </a>
                    </div>
                    @endif
                </div>

                @php
                    $collageItems = collect($fallbackScopri)
                        ->filter(fn ($item) => !empty($item->img))
                        ->values();
                @endphp
                
                @if($collageItems->count() > 0)
                    <div class="borgo-imgs rotating-discover-collage" data-interval="4500">
                        @foreach($collageItems->take(3) as $index => $item)
                            <button
                                type="button"
                                class="rotating-collage-item {{ $index === 0 ? 'bi1 is-active' : ($index === 1 ? 'bi2' : 'bi3') }}"
                                data-index="{{ $index }}"
                                data-gallery='@json($collageItems->map(fn ($photo) => ["type" => "image", "url" => $photo->img, "alt" => app()->getLocale() === "en" && !empty($photo->nome_en) ? $photo->nome_en : $photo->nome]))'
                                aria-label="{{ app()->getLocale() === 'en' && !empty($item->nome_en) ? $item->nome_en : $item->nome }}"
                            >
                                <img
                                    src="{{ $item->img }}"
                                    alt="{{ app()->getLocale() === 'en' && !empty($item->nome_en) ? $item->nome_en : $item->nome }}"
                                    loading="lazy"
                                    decoding="async"
                                />
                            </button>
                        @endforeach
                    </div>
                @endif
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
