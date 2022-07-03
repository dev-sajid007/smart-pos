
<div class="btn-group btn-corner">
    @include('admin.includes.user-log', ['data' => $product])

    <a href="{{ route('products.show', $product->id) }}"class="btn btn-sm btn-primary">
        <i class="fa fa-eye"></i>
    </a>

    @if(hasPermission('products.edit'))
        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-success">
            <i class="fa fa-edit"></i>
        </a>
    @endif

    @if(hasPermission('products.destroy'))
        <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" onclick="deleteData({{ $product->id }})" data-target="#DeleteModal">
            <i class="fa fa-trash"></i>
        </a>
    @endif
</div>
