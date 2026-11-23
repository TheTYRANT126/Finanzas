@props(['paginator'])
@if($paginator->hasPages())
    <nav class="pagination-custom" aria-label="Paginación">
        <span>{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} de {{ $paginator->total() }}</span>
        <div>
            @if($paginator->previousPageUrl())
                <a class="button secondary compact" href="{{ $paginator->previousPageUrl() }}">Anterior</a>
            @endif
            <span>Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}</span>
            @if($paginator->nextPageUrl())
                <a class="button secondary compact" href="{{ $paginator->nextPageUrl() }}">Siguiente</a>
            @endif
        </div>
    </nav>
@endif
