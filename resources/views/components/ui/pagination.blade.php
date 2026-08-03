@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="mk-ui-pagination" aria-label="Pagination">
        {{ $paginator->links() }}
    </nav>
@endif
