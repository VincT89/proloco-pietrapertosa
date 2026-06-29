<div class="section-hero">
    <div class="section-hero-bg">
        <img class="bgi-img object-{{ $bgPosition ?? 'center' }}" src="{{ $img }}" alt="" fetchpriority="high" loading="eager" decoding="async" />
        <div class="hero-gradient" style="background: rgba(0,0,0,{{ $page?->hero_overlay_opacity ?? 0.4 }});"></div>
    </div>
    <div class="section-hero-content fad">
        @if(isset($subtitle) && $subtitle)
            <span class="lbl-mut section-hero-subtitle">{{ $subtitle }}</span>
        @endif
        <h1 class="wr section-hero-title">
            {{ $title }}
        </h1>
        @if(isset($page) && $page?->getTranslation('hero_cta_text') && $page?->hero_cta_url)
            <div style="margin-top: 30px;">
                <a href="{{ $page->hero_cta_url }}" class="ed-btn ed-btn-gold">{{ $page->getTranslation('hero_cta_text') }}</a>
            </div>
        @endif
        @if(isset($extra_html))
            {!! $extra_html !!}
        @endif
    </div>
</div>
