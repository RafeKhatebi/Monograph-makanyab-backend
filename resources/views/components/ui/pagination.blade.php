@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="mk-ui-pagination" aria-label="{{ __('common.pagination') }}">
        {{ $paginator->links() }}
    </nav>
@endif
