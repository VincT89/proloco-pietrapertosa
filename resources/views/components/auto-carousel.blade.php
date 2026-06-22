<div class="auto-carousel" data-interval="{{ $interval ?? 4000 }}" data-fit="{{ $objectFit ?? 'cover' }}">
    @foreach($images as $idx => $imgUrl)
        <img 
            src="{{ $imgUrl }}" 
            alt="Carousel Image {{ $idx + 1 }}" 
            class="carousel-img {{ $idx === 0 ? 'active' : '' }} object-{{ $objectFit ?? 'cover' }}"
        />
    @endforeach

    @if(count($images) > 1)
        <div class="carousel-dots">
            @foreach($images as $idx => $imgUrl)
                <div class="carousel-dot {{ $idx === 0 ? 'active' : '' }}" data-index="{{ $idx }}"></div>
            @endforeach
        </div>
    @endif
</div>
