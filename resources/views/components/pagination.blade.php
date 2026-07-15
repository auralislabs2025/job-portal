@props(['paginator'])
@if ($paginator->hasPages())
    @php
        $window = 2;
        $last = $paginator->lastPage();
        $current = $paginator->currentPage();
        $start = max(1, $current - $window);
        $end = min($last, $current + $window);
    @endphp
    <div class="pagination">
        @if ($paginator->onFirstPage())
            <span class="pagination-link pagination-disabled">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link">Previous</a>
        @endif

        @if ($start > 1)
            <a href="{{ $paginator->url(1) }}" class="pagination-link">1</a>
            @if ($start > 2)
                <span class="pagination-link pagination-disabled">...</span>
            @endif
        @endif

        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $current)
                <span class="pagination-link pagination-active">{{ $page }}</span>
            @else
                <a href="{{ $paginator->url($page) }}" class="pagination-link">{{ $page }}</a>
            @endif
        @endfor

        @if ($end < $last)
            @if ($end < $last - 1)
                <span class="pagination-link pagination-disabled">...</span>
            @endif
            <a href="{{ $paginator->url($last) }}" class="pagination-link">{{ $last }}</a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link">Next</a>
        @else
            <span class="pagination-link pagination-disabled">Next</span>
        @endif
    </div>
@endif
