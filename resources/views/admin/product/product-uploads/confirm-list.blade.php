@extends('admin.master')

@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-th-list"></i> Product Information</h1>
                <p>Product information</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <div class="tile">
                    <h3 class="tile-title">Product List
                        <a href="{{ route('confirm-list-all-products.delete') }}" class="btn btn-danger float-right">Delete All</a>
                    </h3>


                    <div class="tile-body" style="min-height: 450px">
                        <form action="{{ route('confirm-list-products.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="company_id" value="{{ auth()->user()->fk_company_id }}">
                            <input type="hidden" name="created_by" value="{{ auth()->user()->user_id }}">
                            <input type="hidden" name="updated_by" value="{{ auth()->user()->user_id }}">


                            <button class="btn btn-primary" {{ count($products) == 0 ? 'disabled' : '' }} type="submit" type="submit">
                                <i class="fa fa-fw fa-lg fa-check-circle"></i> Upload First 50 Product

                            </button>
                            <br><br>

                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sl.</th>
                                                <th>Name</th>
                                                <th>Code</th>
                                                <th>Category</th>
                                                <th>Brand</th>
                                                <th>Unit</th>
                                                <th>Purchase Price</th>
                                                <th>Sale Price</th>
                                                <th>Tax</th>
                                                <th>Opening Qty</th>
                                                <th>Alert Qty</th>
                                                <th>Expire Date</th>
                                                <th>Barcode</th>
                                                <th>Generic</th>
                                                <th>Rak No</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($products as $key => $product)
                                                <tr>
                                                    <td>{{ $key + $products->firstItem() }}</td>
                                                    <td>{{ $product->product_name }}</td>
                                                    <td>{{ $product->product_code }}</td>
                                                    <td>{{ $product->fk_category_id }}</td>
                                                    <td>{{ $product->brand_id }}</td>
                                                    <td>{{ $product->fk_product_unit_id }}</td>
                                                    <td>{{ $product->product_cost }}</td>
                                                    <td>{{ $product->product_price }}</td>
                                                    <td>{{ $product->tax }}</td>
                                                    <td>{{ $product->opening_quantity }}</td>
                                                    <td>{{ $product->product_alert_quantity }}</td>
                                                    <td>{{ $product->expire_date }}</td>
                                                    <td>{{ $product->barcode_id }}</td>
                                                    <td>{{ $product->generic_id }}</td>
                                                    <td>{{ $product->product_rak_no }}</td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-danger" data-toggle="modal"
                                                           onclick="deleteData({{ $product->id }})"
                                                           data-target="#DeleteModal">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @include('admin.includes.pagination', ['data' => $products])
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.includes.delete_confirm')
    </main>

    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap.min.js')}}"></script>


    <script type="text/javascript">$('#product_import_table').DataTable();</script>


    <script type="text/javascript">
        function deleteData(id) {
            var id = id;
            var url = '{{ route("confirm-list-products.delete", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>

@endsection
