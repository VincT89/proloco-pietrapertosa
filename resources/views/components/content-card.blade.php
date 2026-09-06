@php
    $title = $item->getTranslation('title');
    $cover = $kind === 'tradition' ? $item->galleryMedia->firstWhere('type', 'image') : $item->cover;
    $href = match ($kind) {
        'news' => route('news.show.'.app()->getLocale(), $item->slug),
        'event' => route('events.show.'.app()->getLocale(), $item->slug),
        default => route('traditions.show.'.app()->getLocale(), $item->id),
    };
    $description = $kind === 'news' ? ($item->getTranslation('excerpt') ?: $item->getTranslation('content')) : $item->getTranslation('description');
    $excerpt = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags(preg_replace('/<\/(p|div|li|h[1-6])>/i', ' ', $description ?? '')))), 180);
@endphp
<article class="content-card">
    @if($cover)
        @php($coverUrl = $cover->optimizedUrl('poster'))
        <a href="{{ $href }}" class="content-card-image {{ $kind === 'tradition' ? 'is-photo' : 'cover-frame' }}" tabindex="-1" aria-hidden="true">
            @if($kind !== 'tradition')
                <img class="cover-backdrop" src="{{ $coverUrl }}" alt="" aria-hidden="true" loading="lazy" decoding="async">
            @endif
            <img class="cover-foreground" src="{{ $coverUrl }}" alt="" loading="lazy" decoding="async">
        </a>
    @endif
    <div class="content-card-copy">
        <p class="content-meta">
            @if($kind === 'news')
                <time datetime="{{ ($item->published_at ?? $item->created_at)->toDateString() }}">{{ ($item->published_at ?? $item->created_at)->translatedFormat('j F Y') }}</time>
            @elseif($kind === 'event')
                @include('components.event-date', ['event' => $item])
            @else
                {{ $item->getTranslation('subtitle') }}
            @endif
        </p>
        <h3><a href="{{ $href }}">{{ $title }}</a></h3>
        @if($excerpt)<p class="content-card-excerpt">{{ html_entity_decode($excerpt) }}</p>@endif
        <a class="content-read-more" href="{{ $href }}" aria-label="{{ (app()->getLocale() === 'en' ? 'Read: ' : 'Leggi: ').$title }}">{{ app()->getLocale() === 'en' ? 'Read more' : 'Leggi di più' }}</a>
    </div>
</article>
