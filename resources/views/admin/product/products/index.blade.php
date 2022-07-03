@extends('admin.master')
@section('title', ' - Product List')

@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">

                <div class="tile">
                    @if(hasPermission('products.create'))
                        <a href="{{ route('products.create') }}" class="btn btn-primary" style="float: right;">
                            <i class="fa fa-plus"></i> Add Product
                        </a>
                    @endif

                    <h3 class="tile-title"><i class="fa fa-list"></i> Product List </h3>

                    <!-- Product Search Filter -->
                    @include('admin.product.products.includes.filter-data')

                    <div class="tile-body table-responsive">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th>Supplier</th>
                                    <th>Name</th>
                                    <th>Product Code</th>
                                    <th>Unit</th>
                                    <th>Serial</th>
                                    <th class="text-right">Price</th>
                                    <th class="text-center">Image</th>
                                    <th width="10%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($products as $key => $product)
                                    <tr>
                                        <td>{{ $key + $products->firstItem() }}</td>
                                        <td>{{ optional($product->supplier)->name }}</td>
                                        <td>{{ $product->product_name }}</td>
                                        <td>{{ $product->product_code }}</td>
                                        <td>{{ $product->product_unit->name }}</td>
                                        <td>
                                            @if ($product->has_serial==1)
                                                <span class="badge badge-success">Yes</span>
                                                @else
                                                <span class="badge badge-danger">No</span>
                                            @endif
                                        </td>
                                        <td class="text-right">{{ $product->product_price }}</td>
                                        <td class="text-center">
                                            @if (file_exists($product->image))
                                                <img width="120px" height="80px" src="{{ asset($product->image) }}">
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @include('admin.product.products.includes.actions')
                                        </td>
                                    </tr>
                                @endforeach

                                @include('admin.includes.delete_confirm')
                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $products])
                    </div>
                </div>
            </div>
        </div>
    </main>


    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>

    <script type="text/javascript">
        function deleteData(id) {
            var id = id;
            var url = '{{ route("products.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }
        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>
@endsection
