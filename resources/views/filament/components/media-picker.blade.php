@php
    $media = $getSelectedMedia();
    $usage = app(\App\Services\MediaUsage::class)->forMedia($media);
@endphp
<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="media-picker" x-data="mediaPicker({ state: $wire.$entangle('{{ $getStatePath() }}').live, multiple: @js($isMultiple()) })">
        @unless ($isDisabled())
            <div class="media-picker-actions">{{ $getAction('chooseMedia') }} {{ $getAction('uploadMedia') }}</div>
        @endunless
        @if ($media->isEmpty())
            <p class="media-picker-empty">Nessun file selezionato.</p>
        @else
            <ul class="media-picker-selected">
                @foreach ($media as $item)
                    <li wire:key="{{ $getStatePath() }}-selected-{{ $item->id }}">
                        @include('filament.components.media-preview', ['item' => $item])
                        <div class="media-picker-info">
                            <p class="media-picker-name">{{ $item->displayName() }}</p>
                            @include('filament.components.media-usage', ['uses' => $usage[$item->id] ?? []])
                            @unless ($isDisabled())
                                <div class="media-picker-file-actions">
                                    @if ($isMultiple())
                                        <button type="button" @click="move({{ $item->id }}, -1)" @disabled($loop->first) aria-label="Sposta prima {{ $item->displayName() }}">Sposta prima</button>
                                        <button type="button" @click="move({{ $item->id }}, 1)" @disabled($loop->last) aria-label="Sposta dopo {{ $item->displayName() }}">Sposta dopo</button>
                                    @endif
                                    <button type="button" @click="remove({{ $item->id }})" aria-label="Rimuovi dalla selezione {{ $item->displayName() }}">Rimuovi dalla selezione</button>
                                </div>
                            @endunless
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
        @include('filament.components.media-zoom')
    </div>
</x-dynamic-component>
