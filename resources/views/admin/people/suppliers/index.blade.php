@extends('admin.master')
@section('title', ' - Suppliers')
@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">

                <div class="tile">
                    @if(hasPermission('suppliers.create'))
                    <a href="{{ route('suppliers.create') }}" class="btn btn-primary" style="float: right;"><i class="fa fa-plus"></i> Add Supplier</a>
                    @endif
                    <h3 class="tile-title"> Supplier List </h3>

                    <!-- filter -->
                    <div class="row">
                        <div class="col-sm-8 mx-auto">
                            <form action="" method="get">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Search Supplier</span>
                                    </div>
                                    <input class="form-control form-control-md" name="search" placeholder="Search anything.........." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button style="cursor: pointer" type="submit" class="input-group-text"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <hr>
                    <div class="tile-body table-responsive">
                        <table class="table table-hover table-bordered table-sm" id="">
                            <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th width="8%">Code</th>
                                    <th>Name</th>
                                    <th>Company Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th class="text-right">Balance</th>
                                    <th width="10%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($suppliers as $key => $supplier)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $supplier->supplier_code }}s</td>
                                        <td>{{ $supplier->name }}</td>
                                        <td>{{ $supplier->company_name }}</td>
                                        <td>{{ $supplier->email }}</td>
                                        <td>{{ $supplier->phone }}</td>
                                        <td>{{ $supplier->address }}</td>

                                        <td class="text-right">{{ number_format($supplier->current_balance, 2) }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                @include('admin.includes.user-log', ['data' => $supplier])
                                                <a class="btn btn-success btn-sm" title="View"
                                                   href="{{ route('suppliers.show', $supplier->id) }}"> <i class="fa fa-eye"></i>
                                                </a>

                                                @if(hasPermission('suppliers.create'))
                                                    <a class="btn btn-primary btn-sm" title="Edit" href="{{ route('suppliers.edit', $supplier->id) }}"> <i class="fa fa-edit"></i> </a>
                                                @endif

                                                @if(hasPermission('suppliers.destroy'))
                                                    <a title="Delete Supplier"
                                                       href="#" class="btn btn-danger btn-sm"
                                                       data-toggle="modal" onclick="deleteData({{ $supplier->id }})"
                                                       data-target="#DeleteModal"><i class="fa fa-trash"></i></a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                @include('admin.includes.delete_confirm')
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th colspan="7"><strong>Total: </strong></th>
                                    <th class="text-right">{{ number_format($suppliers->sum('current_balance'), 2) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>

                       @include('admin.includes.pagination', ['data' => $suppliers])
                    </div>
                </div>
            </div>
        </div>
    </main>


@endsection


@section('footer-script')
    <script type="text/javascript">
        function deleteData(id) {
            var id = id;
            var url = '{{ route("suppliers.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }


    </script>
@endsection
