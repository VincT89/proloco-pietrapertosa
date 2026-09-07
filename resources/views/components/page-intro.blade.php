@php
    $hasIntroTitle = filled(trim(strip_tags($title ?? '')));
    $hasIntroText = filled(trim(html_entity_decode(strip_tags($text ?? ''), ENT_QUOTES, 'UTF-8'), " \t\n\r\0\x0B\u{00A0}"))
        || preg_match('/<(img|video|audio|iframe|table)\b/i', $text ?? '');
@endphp
@if($hasIntroTitle || $hasIntroText)
<div class="wrap page-intro-wrap {{ ($compact ?? false) ? 'page-intro-compact' : '' }}">
    <div class="page-intro-inner fad">
        @if($hasIntroTitle)
        <h2 class="page-intro-title">
            {{ $title }}
        </h2>
        @endif
        @if($hasIntroText)
        <div class="page-intro-text">
            {!! clean($text) !!}
        </div>
        @endif
    </div>
</div>
@endif
