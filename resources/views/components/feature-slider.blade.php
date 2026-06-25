@props(['items' => collect()])

@if($items->count())
    <div class="discover-feature-slider" data-interval="6500">
        @foreach($items as $index => $item)
            @php
                $allMedia = $item->galleryMedia ?? collect();
                if (method_exists($item, 'externalMedia') && $item->externalMedia) {
                    $allMedia = $allMedia->concat($item->externalMedia);
                }
                
                $images = $allMedia->filter(function($m) {
                    return !$m->isVideo();
                })->take(5)->values();

                $galleryData = $images->map(function($m) {
                    return [
                        'url' => $m->optimizedUrl('default') ?? $m->url,
                        'type' => 'image',
                        'provider' => $m->provider,
                        'embed_url' => $m->embed_url,
                        'alt' => $m->alt
                    ];
                })->toJson();
                
                $title = $item->getTranslation('title');
                $subtitle = $item->getTranslation('subtitle');
                $description = $item->getTranslation('description');
                $contact = $item->getTranslation('contact_info');
            @endphp

            <article class="discover-feature-slide {{ $index === 0 ? 'is-active' : '' }}" data-index="{{ $index }}">
                <div class="discover-feature-copy">
                    <h2 class="discover-feature-title">{{ $title }}</h2>

                    @if($subtitle)
                        <p class="discover-feature-subtitle">{{ $subtitle }}</p>
                    @endif

                    @if($description)
                        <div class="discover-feature-description">
                            {!! clean($description) !!}
                        </div>
                    @endif

                    @if($contact)
                        <div class="discover-feature-contact">
                            {!! nl2br(e($contact)) !!}
                        </div>
                    @endif
                </div>

                @if($images->count())
                    <div class="discover-feature-media">
                        @foreach($images as $imgIndex => $img)
                            <div class="discover-collage-img discover-collage-img-{{ $imgIndex + 1 }}" onclick="openGallery({{ $galleryData }}, {{ $imgIndex }})" style="cursor: pointer;">
                                <img
                                    src="{{ $img->optimizedUrl($imgIndex === 0 ? 'large' : 'card') }}"
                                    alt="{{ $title }}"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </div>
                        @endforeach
                    </div>
                @endif
            </article>
        @endforeach

        @if($items->count() > 1)
            <div class="discover-feature-controls">
                <button type="button" class="discover-feature-prev" aria-label="Precedente">←</button>

                <div class="discover-feature-dots">
                    @foreach($items as $index => $item)
                        <button
                            type="button"
                            class="discover-feature-dot {{ $index === 0 ? 'is-active' : '' }}"
                            data-index="{{ $index }}"
                            aria-label="Vai alla slide {{ $index + 1 }}"
                        ></button>
                    @endforeach
                </div>

                <button type="button" class="discover-feature-next" aria-label="Successivo">→</button>
            </div>
        @endif
    </div>
@endif
