@if($paginator->hasPages())<nav class="pagination" aria-label="Pagination">{{ $paginator->onEachSide(1)->links() }}</nav>@endif
