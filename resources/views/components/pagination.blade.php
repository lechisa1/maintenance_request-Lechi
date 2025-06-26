<!-- resources/views/components/pagination.blade.php -->
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
</style>
