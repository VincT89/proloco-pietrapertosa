@extends('layouts.app')
@section('title', ($page?->getTranslation('hero_title') ?? __('news.hero_title')).' · Proloco Pietrapertosana')
@section('content')
    @include('components.section-hero', [
        'title' => $page?->getTranslation('hero_title') ?? __('news.hero_title'),
        'subtitle' => $page?->getTranslation('hero_subtitle') ?? __('news.hero_subtitle'),
        'img' => $page?->heroMedia?->optimizedUrl('hero') ?? asset('images/pietrapertosaBacheca.jpg'),
        'bgPosition' => 'center 30%'
    ])
    @include('components.page-intro', [
        'compact' => true,
        'title' => $page?->getTranslation('intro_title') ?? __('news.intro_title'),
        'text' => $page?->getTranslation('intro_text') ?? __('news.intro_text')
    ])
    <section id="news-list" class="wrap content-list-section" aria-label="{{ __('navigation.news') }}">
        <div class="content-grid">
            @forelse($news as $item)
                @include('components.content-card', ['kind' => 'news'])
            @empty
                <p>{{ app()->getLocale() === 'en' ? 'No news available at the moment.' : 'Nessuna notizia disponibile al momento.' }}</p>
            @endforelse
        </div>
        {{ $news->links('components.pagination') }}
    </section>
@endsection
