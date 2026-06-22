@props(['provider', 'title', 'src', 'ratio' => '16-9'])

<div class="external-embed external-embed-ratio-{{ $ratio }}" data-external-embed="true" data-src="{{ $src }}" data-provider="{{ $provider }}">
    <div class="external-embed-placeholder">
        <div class="external-embed-text">
            <svg class="ico mb-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            <p>{{ __('legal.external_blocked') }}</p>
            <p><small>{{ $title }}</small></p>
            <button class="ed-btn ed-btn-small external-embed-button">{{ __('legal.enable_external') }}</button>
        </div>
    </div>
</div>
