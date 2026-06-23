<div class="borgo-imgs interactive-collage collage-container">
    @php 
        $displayImages = is_object($images) && method_exists($images, 'slice') ? $images->slice(0, 3) : array_slice($images, 0, 3); 
        $collId = $id ?? \Illuminate\Support\Str::random(6);
        
        $galleryJson = "[]";
        if (is_object($images) && method_exists($images, 'map')) {
            $galleryJson = $images->map(function($m) {
                return [
                    'type' => $m->type ?? 'image',
                    'provider' => $m->provider ?? '',
                    'url' => (isset($m->type) && $m->type === 'image') ? $m->optimizedUrl('large') : ($m->url ?? ''),
                    'embed_url' => $m->embed_url ?? ''
                ];
            })->toJson();
        } elseif (is_array($images)) {
            $galleryJson = json_encode(array_map(function($m) {
                return ['type' => 'image', 'url' => is_string($m) ? $m : ''];
            }, $images));
        }
    @endphp
    @foreach($displayImages as $idx => $mediaItem)
        @php 
            $isObj = is_object($mediaItem);
            $imgUrl = $isObj ? $mediaItem->optimizedUrl('card') : $mediaItem;
        @endphp
        <div 
            class="borgo-img-wrap collage-item"
            id="coll-{{ $collId }}-{{ $idx }}"
            data-index="{{ $idx }}"
            data-rank="{{ $idx }}"
            onclick="openGallery({{ htmlspecialchars($galleryJson, ENT_QUOTES, 'UTF-8') }}, {{ $idx }})"
        >
            @if($isObj)
                <div style="width: 100%; height: 100%; pointer-events: none;">
                    <x-media-renderer :media="$mediaItem" class="collage-img" />
                </div>
            @else
                <img src="{{ $imgUrl }}" alt="{{ $altText ?? '' }} {{ $idx + 1 }}" class="collage-img" />
            @endif
            
            <div class="collage-overlay {{ $idx === 0 ? 'collage-first-overlay' : '' }}">
                @if($idx > 0 && $loop->last && count($images) > 3)
                <div class="collage-label">
                    +{{ count($images) - 3 }}
                </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
