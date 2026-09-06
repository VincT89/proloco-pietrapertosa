@php
    $mediaItems = $mediaItems->values();
    $galleryData = $mediaItems->map(fn ($m) => [
        'type' => $m->type, 'provider' => $m->provider,
        'url' => $m->type === 'image' ? $m->optimizedUrl('large') : ($m->type === 'video' ? $m->optimizedVideoUrl() : $m->url),
        'embed_url' => $m->embed_url, 'alt' => $galleryTitle,
    ]);
@endphp
<div class="media-gallery-group" data-gallery='@json($galleryData)'>
<div class="media-gallery">
    @foreach($mediaItems->take($previewLimit ?? $mediaItems->count()) as $index => $media)
        @php($thumb = $media->isVideo() ? ($media->thumbnail_url ?: $media->videoThumbnailUrl('card')) : $media->optimizedUrl('card'))
        <button type="button" class="media-gallery-item" data-gallery-index="{{ $index }}" aria-label="{{ (app()->getLocale() === 'en' ? 'Open ' : 'Apri ').($media->isVideo() ? 'video' : (app()->getLocale() === 'en' ? 'photo' : 'foto')).' '.($index + 1).' — '.$galleryTitle }}">
            @if($thumb)<img src="{{ $thumb }}" alt="" loading="lazy" decoding="async">@endif
            @if($media->isVideo())<span class="media-video-label">Video</span>@endif
        </button>
    @endforeach
</div>
@if(isset($previewLimit) && $mediaItems->count() > $previewLimit)
    <button type="button" class="content-read-more gallery-view-all" data-gallery-index="{{ $previewLimit }}">{{ app()->getLocale() === 'en' ? 'View the complete album' : 'Guarda l’album completo' }} ({{ $mediaItems->count() }})</button>
@endif
</div>
