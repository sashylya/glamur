@if ($paginator->hasPages())
    <nav class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="disabled">« Назад</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">« Назад</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="disabled">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">Вперед »</a>
        @else
            <span class="disabled">Вперед »</span>
        @endif
    </nav>
@endif

<style>
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 40px;
}

.pagination a, .pagination span {
    padding: 10px 18px;
    background: #1d1e27;
    border: 1px solid #2b2d39;
    border-radius: 8px;
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
}

.pagination a:hover {
    background: #df5d83;
    border-color: #df5d83;
}

.pagination span.active {
    background: #df5d83;
    border-color: #df5d83;
    cursor: default;
}

.pagination span.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #15161d;
}
</style>
