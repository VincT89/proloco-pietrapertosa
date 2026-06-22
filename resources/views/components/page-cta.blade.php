<section class="cta cta-no-padding">
    <div class="bgi cta-bgi">
        @if(isset($bgImage))
            <img src="{{ $bgImage }}" alt="" aria-hidden="true" class="cta-bg-img" />
        @endif
    </div>
    <div class="in">
        <span class="lbl fad cta-subtitle">{{ $subtitle ?? 'Vieni a trovarci' }}</span>
        <h2 class="wr">{{ $title }}</h2>
        <p class="fad cta-text">{{ $text }}</p>
        <div class="cta-buttons-wrapper">
            @if(isset($buttons) && is_array($buttons) && count($buttons) > 0)
                @foreach($buttons as $btn)
                    <a href="{{ $btn['link'] ?? '#' }}" {!! str_starts_with($btn['link'] ?? '', 'http') ? 'target="_blank" rel="noopener noreferrer"' : '' !!} class="cta-line fad cta-line-visible">
                        <span class="ring">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ico">{!! $btn['iconPath'] ?? '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>' !!}</svg>
                        </span> 
                        {{ $btn['text'] }}
                    </a>
                @endforeach
            @elseif(isset($btnText))
                <a href="{{ $btnLink ?? '#' }}" {!! str_starts_with($btnLink ?? '', 'http') ? 'target="_blank" rel="noopener noreferrer"' : '' !!} class="cta-line fad cta-line-visible">
                    <span class="ring">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ico">{!! $iconPath ?? '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>' !!}</svg>
                    </span> 
                    {{ $btnText }}
                </a>
            @endif
        </div>
    </div>
</section>
