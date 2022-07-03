@extends('admin.master')

@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-th-list"></i> Customer Information</h1>
                <p>Customer information</p>
            </div>

            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Customer Information</li>
                <li class="breadcrumb-item active"><a href="#">Customer Information Table</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                @if(Session::get('message'))
                    <div class="alert alert-success">
                        {{ Session::get('message') }}
                    </div>
                @endif

                <div class="tile">
                    <h3 class="tile-title">Customer List
                        <a href="{{ route('confirm-list-all-customers.delete') }}" class="btn btn-danger float-right">Delete All</a>
                    </h3>
                    <div class="tile-body" style="min-height: 750px">
                        <form action="{{ route('customer-confirm-list.store') }}" method="post" style="width: 100%">
                            @csrf

                            <div class="form-group">
                                <div class="">
                                    <button class="btn btn-primary" type="submit" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Import First 50 Customer</button>
                                </div>
                            </div>

                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sl.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Category</th>
                                        <th class="text-right">Opening Balance</th>
                                        <th class="text-right">Due Limit</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($customers as $key => $customer)
                                        <tr>
                                            <td>{{ $key + $customers->firstItem() }}</td>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ $customer->email }}</td>
                                            <td>{{ $customer->phone }}</td>
                                            <td>{{ $customer->address }}</td>
                                            <td>{{ $customer->customer_category }}</td>
                                            <td class="text-right">{{ $customer->opening_due }}</td>
                                            <td class="text-right">{{ $customer->due_limit }}</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" onclick="deleteData({{ $customer->id }})" data-target="#DeleteModal">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </form>
                        @include('admin.includes.pagination', ['data' => $customers])

                        <br>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.includes.delete_confirm')
    </main>

    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>


    <script type="text/javascript">
        function deleteData(id) {
            var id = id;
            var url = '{{ route("confirm-list-customers.delete", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }
        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>

@endsection
