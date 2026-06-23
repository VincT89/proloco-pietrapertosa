@props(['media', 'class' => '', 'preset' => 'thumb'])

@if($media)
    @if($media->type === 'image')
        <img src="{{ $media->optimizedUrl($preset) }}" alt="{{ $media->alt ?? '' }}" class="{{ $class }}">
    @elseif($media->type === 'video')
        @if($media->provider === 'cloudinary')
            <video controls preload="metadata" poster="{{ $media->thumbnail_url ?? '' }}" class="w-full h-auto {{ $class }}">
                <source src="{{ $media->url }}" type="video/mp4">
                Il tuo browser non supporta il tag video.
            </video>
        @elseif($media->provider === 'facebook')
            <div class="media-responsive-wrapper ratio-16-9 {{ $class }}">
                <x-external-embed 
                    :provider="$media->provider" 
                    title="Facebook Video" 
                    :src="$media->embed_url" 
                    ratio="16-9" 
                />
            </div>
        @elseif($media->provider === 'instagram')
            <div class="media-responsive-wrapper ratio-1-1 {{ $class }}">
                <x-external-embed 
                    :provider="$media->provider" 
                    title="Instagram Post" 
                    :src="$media->embed_url" 
                    ratio="1-1" 
                />
            </div>
            <div class="media-ig-link-wrapper">
                <a href="{{ $media->url }}" target="_blank" class="media-ig-link">Apri su Instagram</a>
            </div>
        @endif
    @endif
@endif
