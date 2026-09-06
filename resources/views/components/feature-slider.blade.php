@props(['items' => collect()])
@if($items->isNotEmpty())
    <div class="place-browser">
        @if($items->count() > 1)
            <details class="place-index-mobile">
                <summary>{{ app()->getLocale() === 'en' ? 'Choose what to explore' : 'Scegli cosa esplorare' }}</summary>
                <ul>
                    @foreach($items as $item)
                        <li><a href="#place-{{ $item->id }}">{{ $item->getTranslation('title') }}</a></li>
                    @endforeach
                </ul>
            </details>
            <nav class="place-index" aria-label="{{ app()->getLocale() === 'en' ? 'Explore this section' : 'Esplora questa sezione' }}">
                @foreach($items as $item)
                    <a href="#place-{{ $item->id }}">{{ $item->getTranslation('title') }}</a>
                @endforeach
            </nav>
        @endif
        <div class="place-articles">
            @foreach($items as $item)
                <article id="place-{{ $item->id }}" class="place-article">
                    <div class="place-copy">
                        <h2>{{ $item->getTranslation('title') }}</h2>
                        @if($item->getTranslation('subtitle'))<p class="place-subtitle">{{ $item->getTranslation('subtitle') }}</p>@endif
                        <div class="rich-content">{!! clean($item->getTranslation('description') ?? '') !!}</div>
                        @if($item->getTranslation('contact_info'))<div class="place-contact">{!! nl2br(e($item->getTranslation('contact_info'))) !!}</div>@endif
                    </div>
                    @if($item->galleryMedia->isNotEmpty() || $item->externalMedia->isNotEmpty())
                        <div class="place-media">
                            @include('components.media-gallery', ['mediaItems' => $item->galleryMedia->concat($item->externalMedia), 'galleryTitle' => $item->getTranslation('title'), 'previewLimit' => 3])
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
@endif
