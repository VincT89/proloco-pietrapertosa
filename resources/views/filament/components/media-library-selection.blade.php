@php
    $page = $getMediaPage();
    $usage = app(\App\Services\MediaUsage::class)->forMedia($page->getCollection());
@endphp
<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="media-picker" x-data="mediaPicker({ state: $wire.$entangle('{{ $getStatePath() }}'), multiple: @js($isMultiple()) })">
        <p class="media-picker-help">{{ $isMultiple() ? 'Seleziona i file da usare. La selezione viene mantenuta anche durante la ricerca.' : 'Seleziona il file da usare.' }}</p>
        <ul class="media-library-grid">
            @forelse ($page as $item)
                <li wire:key="{{ $getStatePath() }}-library-{{ $item->id }}" :class="{ 'is-selected': has({{ $item->id }}) }">
                    @include('filament.components.media-preview', ['item' => $item])
                    <label class="media-library-choice">
                        <input type="{{ $isMultiple() ? 'checkbox' : 'radio' }}" name="{{ $getStatePath() }}" :checked="has({{ $item->id }})" @change="choose({{ $item->id }})" value="{{ $item->id }}">
                        <span class="media-picker-name">{{ $item->displayName() }}</span>
                    </label>
                    @if ($item->alt && $item->alt !== $item->displayName())<p class="media-picker-help">{{ $item->alt }}</p>@endif
                    @include('filament.components.media-usage', ['uses' => $usage[$item->id] ?? []])
                </li>
            @empty
                <li class="media-picker-empty">Nessun file trovato. Prova un altro nome o carica un nuovo file.</li>
            @endforelse
        </ul>
        @if ($page->hasPages())
            <nav class="media-picker-pagination" aria-label="Pagine della libreria">
                <button type="button" @disabled($page->onFirstPage()) wire:click="$set('{{ $getPageStatePath() }}', {{ $page->currentPage() - 1 }})">Precedente</button>
                <span>Pagina {{ $page->currentPage() }} di {{ $page->lastPage() }}</span>
                <button type="button" @disabled(! $page->hasMorePages()) wire:click="$set('{{ $getPageStatePath() }}', {{ $page->currentPage() + 1 }})">Successiva</button>
            </nav>
        @endif
        @include('filament.components.media-zoom')
    </div>
</x-dynamic-component>
