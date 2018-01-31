<ul class="pagination">
    @if ($page_query_param <= 1)
        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
    @else
        <li class="page-item"><a class="page-link" href="{{ $prev_page_url }}" rel="prev">&laquo;</a></li>
    @endif
    <li class="info-page-item">{{ $page_query_param . '/' . $total_page }}</li>
    @if ($page_query_param >= $total_page)
        <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
    @else
        <li class="page-item"><a class="page-link" href="{{ $next_page_url }}" rel="next">&raquo;</a></li>
    @endif
</ul>
