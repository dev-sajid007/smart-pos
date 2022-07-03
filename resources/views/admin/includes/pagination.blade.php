<span class="float-right no-print">
     <ul class="pagination">
        <li class="page-item @if($data->currentPage() == 1) disabled @endif">
            <a class="page-link" href="{{ $data->url(1) }}">← First Page</a>
        </li>
         {{ $data->appends(request()->query()) }}

          <li class="page-item @if($data->lastPage() == $data->currentPage()) disabled @endif">
                <a class="page-link" href="?page={{ $data->lastPage() }}">Last Page →</a>
            </li>
    </ul>

</span>
