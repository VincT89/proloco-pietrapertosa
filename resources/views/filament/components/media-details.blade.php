@if ($item)
    <div class="media-details-preview">
        @if ($item->isVideo())
            <video src="{{ $item->optimizedVideoUrl() }}" controls preload="metadata"></video>
        @elseif ($item->type === 'image')
            <img src="{{ $item->optimizedUrl('card') }}" alt="{{ $item->alt ?: $item->displayName() }}">
        @else
            <a href="{{ $item->url }}" target="_blank" rel="noopener">Apri {{ $item->displayName() }}</a>
        @endif
    </div>
@else
    <p>Nessun file selezionato.</p>
@endif
