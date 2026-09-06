@if($paginator->hasPages())
    <nav class="content-pagination" aria-label="{{ app()->getLocale() === 'en' ? 'Pagination' : 'Paginazione' }}">
        @if($paginator->onFirstPage())
            <span aria-disabled="true">{{ app()->getLocale() === 'en' ? 'Previous' : 'Precedente' }}</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">{{ app()->getLocale() === 'en' ? 'Previous' : 'Precedente' }}</a>
        @endif
        <span class="pagination-current">{{ app()->getLocale() === 'en' ? 'Page' : 'Pagina' }} {{ $paginator->currentPage() }} {{ app()->getLocale() === 'en' ? 'of' : 'di' }} {{ $paginator->lastPage() }}</span>
        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">{{ app()->getLocale() === 'en' ? 'Next' : 'Successiva' }}</a>
        @else
            <span aria-disabled="true">{{ app()->getLocale() === 'en' ? 'Next' : 'Successiva' }}</span>
        @endif
    </nav>
@endif
