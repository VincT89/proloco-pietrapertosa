@if ($matches->isNotEmpty())
    <div class="media-upload-duplicates" role="status">
        <p>Questi file sono già nella libreria. Confermando verranno riutilizzati, senza caricare altre copie.</p>
        <ul>@foreach ($matches as $item)
            <li>@if ($item->previewUrl())<img src="{{ $item->previewUrl() }}" alt="{{ $item->displayName() }}">@endif <span>{{ $item->displayName() }}</span></li>
        @endforeach</ul>
    </div>
@endif
@if ($pending)
    <p class="media-picker-help">Per alcuni file dell’archivio il controllo dei duplicati non è ancora disponibile. Verifica anche le anteprime nella libreria.</p>
@endif
