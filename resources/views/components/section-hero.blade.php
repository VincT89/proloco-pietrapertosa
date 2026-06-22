<div class="section-hero">
    <div class="section-hero-bg">
        <img class="bgi-img" src="{{ $img }}" alt="" class="object-{{ $bgPosition ?? 'center' }}" />
        <div class="hero-gradient"></div>
    </div>
    <div class="section-hero-content fad">
        @if(isset($subtitle) && $subtitle)
            <span class="lbl-mut section-hero-subtitle">{{ $subtitle }}</span>
        @endif
        <h1 class="wr section-hero-title">
            {{ $title }}
        </h1>
    </div>
</div>
