@extends('admin.master')

@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-th-list"></i> Bulk Purchase Information</h1>
                <p>Purchase information</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <div class="tile">
                    <h3 class="tile-title">Bulk Purchase List
                        <a href="{{ route('confirm-list-all-purchases.delete') }}" class="btn btn-danger float-right">Delete All</a>
                    </h3>


                    <div class="tile-body" style="min-height: 750px !important;">

                        <form action="{{ route('bulk.purchase.confirm.store') }}" method="post">
                            @csrf


                            <button class="btn btn-primary" {{ count($purchases) == 0 ? 'disabled' : '' }} type="submit" type="submit">
                                <i class="fa fa-fw fa-lg fa-check-circle"></i> Upload First 20 Product
                            </button>
                            <br><br>


                            <!-- filter section -->
                            @include('admin.purchase.purchases.includes.filter-section')

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
                                                <th>Quantity</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($purchases as $key => $purchase)
                                                <tr>
                                                    <td>{{ $key + $purchases->firstItem() }}</td>
                                                    <td>{{ $purchase->product_name }}</td>
                                                    <td>{{ $purchase->product_code }}</td>
                                                    <td>{{ $purchase->fk_category_id }}</td>
                                                    <td>{{ $purchase->brand_id }}</td>
                                                    <td>{{ $purchase->fk_product_unit_id }}</td>
                                                    <td>{{ $purchase->product_cost }}</td>
                                                    <td>{{ $purchase->quantity }}</td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-danger" data-toggle="modal"
                                                           onclick="deleteData({{ $purchase->id }})"
                                                           data-target="#DeleteModal">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @include('admin.includes.pagination', ['data' => $purchases])
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
            var url = '{{ route("confirm-list-purchases.delete", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>

@endsection
