@if ($uses)
    <details class="media-picker-usage"><summary>Vedi dove è usato</summary>
        <ul>@foreach ($uses as $use)<li>{{ $use }}</li>@endforeach</ul>
    </details>
@else
    <p class="media-picker-help">Nessun utilizzo nei contenuti salvati.</p>
@endif
