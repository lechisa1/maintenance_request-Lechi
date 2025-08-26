{{-- 
<div class="mt-4 d-flex justify-content-center">
    <nav aria-label="Pagination">
        <ul class="pagination pagination-lg">
            <!-- Previous Page Link -->
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">&laquo; Previous</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">&laquo;
                        Previous</a>
                </li>
            @endif

            <!-- Pagination Links -->
            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                <li class="page-item {{ $paginator->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            <!-- Next Page Link -->
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">Next &raquo;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">Next &raquo;</span>
                </li>
            @endif
        </ul>
    </nav>

</div>
<style>
    .pagination .page-item {
        margin: 0 5px;

    }

    .pagination .page-item.active .page-link {
        background-color: #4e73df;

        border-color: #4e73df;

    }

    .pagination .page-link {
        border-radius: 50%;

        /* font-weight: normal; */

        color: #333;

    }

    .pagination .page-link:hover {
        background-color: #1cc88a;

        color: white;

    }

    li {
        font-size: 15px;
    }
</style> --}}
<div class="mt-4 d-flex justify-content-center">
    <nav aria-label="Pagination">
        <ul class="pagination">
            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();
                $start = max($current - 2, 1);
                $end = min($current + 2, $last);
            @endphp

            <!-- First Page -->
            @if ($start > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                </li>
                @if ($start > 2)
                    <li class="page-item disabled ellipsis"><span>...</span></li>
                @endif
            @endif

            <!-- Page Range -->
            @for ($page = $start; $page <= $end; $page++)
                <li class="page-item {{ $page == $current ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                </li>
            @endfor

            <!-- Last Page -->
            @if ($end < $last)
                @if ($end < $last - 1)
                    <li class="page-item disabled ellipsis"><span>...</span></li>
                @endif
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($last) }}">{{ $last }}</a>
                </li>
            @endif
        </ul>
    </nav>
</div>

<style>
    .pagination {
        display: flex;
        gap: 6px;
    }

    /* Circular buttons */
    .pagination .page-link {
        border-radius: 50% !important;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        color: #333;
        border: 1px solid #ddd;
        transition: all 0.3s ease;
        padding: 0 !important;
    }

    /* Active page */
    .pagination .page-item.active .page-link {
        background-color: #11245A;
        border-color: #11245A;
        color: white;
        font-weight: 600;
        box-shadow: 0 0 6px rgba(0,0,0,0.1);
    }

    /* Hover */
    .pagination .page-link:hover {
        background-color: #11245A;
        border-color: #11245A;
        color: white;
    }

    /* Ellipsis */
    .pagination .ellipsis span {
        border: none;
        background: transparent;
        font-size: 16px;
        color: #666;
        padding: 0 6px;
    }
</style>
