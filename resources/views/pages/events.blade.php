@extends('layouts.app')
@section('title', ($page?->getTranslation('hero_title') ?? __('events.hero_title')).' · Proloco Pietrapertosana')
@section('content')
    @include('components.section-hero', [
        'title' => $page?->getTranslation('hero_title') ?? __('events.hero_title'),
        'subtitle' => $page?->getTranslation('hero_subtitle') ?? __('events.hero_subtitle'),
        'img' => $page?->heroMedia?->optimizedUrl('hero') ?? asset('images/PietrapertosaEventi.jpeg')
    ])
    <section class="wrap content-list-section" aria-labelledby="upcoming-title">
        <h2 class="content-section-title" id="upcoming-title">{{ app()->getLocale() === 'en' ? 'Upcoming events' : 'Prossimi appuntamenti' }}</h2>
        <div class="content-grid">
            @forelse($events as $item)
                @include('components.content-card', ['kind' => 'event'])
            @empty
                <p>{{ app()->getLocale() === 'en' ? 'There are no upcoming events at the moment. Explore our annual traditions and past events below.' : 'Al momento non ci sono nuovi appuntamenti in programma. Qui sotto trovi le tradizioni annuali e gli eventi passati.' }}</p>
            @endforelse
        </div>
        {{ $events->links('components.pagination') }}
    </section>
    @if($annualEvents->isNotEmpty())
        <section class="wrap content-list-section traditions-section" aria-labelledby="traditions-title">
            <h2 class="content-section-title" id="traditions-title">@lang('events.annual_title')</h2>
            <div class="content-grid">
                @foreach($annualEvents as $item)
                    @include('components.content-card', ['kind' => 'tradition'])
                @endforeach
            </div>
        </section>
    @endif
    @if($pastEvents->isNotEmpty())
        <section class="wrap content-list-section" aria-labelledby="past-events-title">
            <h2 class="content-section-title" id="past-events-title">{{ app()->getLocale() === 'en' ? 'Past events' : 'Eventi passati' }}</h2>
            <ul class="event-archive">
                @foreach($pastEvents as $pastEvent)
                    <li>
                        <span class="content-meta">@include('components.event-date', ['event' => $pastEvent])</span>
                        <a href="{{ route('events.show.'.app()->getLocale(), $pastEvent->slug) }}">{{ $pastEvent->getTranslation('title') }}</a>
                    </li>
                @endforeach
            </ul>
            {{ $pastEvents->links('components.pagination') }}
        </section>
    @endif
@endsection
