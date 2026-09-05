@if ($item->previewUrl())
    @if ($item->type === 'image')
        <button class="media-picker-preview" type="button" @click="preview(@js($item->optimizedUrl('large')), @js($item->displayName()))" aria-label="Ingrandisci {{ $item->displayName() }}">
            <img src="{{ $item->previewUrl() }}" alt="{{ $item->alt ?: $item->displayName() }}" loading="lazy">
        </button>
    @else
        <a class="media-picker-preview" href="{{ $item->url }}" target="_blank" rel="noopener noreferrer" aria-label="Apri {{ $item->displayName() }} in una nuova scheda">
            <img src="{{ $item->previewUrl() }}" alt="{{ $item->displayName() }}" loading="lazy"><span>Apri video</span>
        </a>
    @endif
@else
    <a class="media-picker-document" href="{{ $item->url }}" target="_blank" rel="noopener noreferrer">Apri {{ $item->type === 'video' ? 'video' : 'documento' }}</a>
@endif
