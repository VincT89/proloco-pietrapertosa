<div class="auto-carousel {{ $className ?? '' }}" data-interval="{{ $interval ?? 4000 }}" data-fit="{{ $objectFit ?? 'cover' }}">
    @foreach($images as $idx => $imgUrl)
        <img 
            src="{{ $imgUrl }}" 
            alt="Carousel Image {{ $idx + 1 }}" 
            class="carousel-img {{ $idx === 0 ? 'active' : '' }} object-{{ $objectFit ?? 'cover' }}"
            loading="lazy" decoding="async"
        />
    @endforeach

    @if(count($images) > 1)
        <div class="carousel-dots">
            @foreach($images as $idx => $imgUrl)
                <button type="button" class="carousel-dot {{ $idx === 0 ? 'active' : '' }}" data-index="{{ $idx }}" aria-label="{{ (app()->getLocale() === 'en' ? 'View image ' : 'Mostra immagine ').($idx + 1) }}"></button>
            @endforeach
        </div>
    @endif
</div>
