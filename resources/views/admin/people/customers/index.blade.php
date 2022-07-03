
@extends('admin.master')
@section('title', 'Customer List')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/custom_css/image_full_screen.css') }}">
    <style>

        th {
            background: #156984 !important;
            height: 25px !important;
            vertical-align: middle !important;
            font-size: 16px !important;
            color: white;
        }
        td {
            font-size: 13px !important;
        }
    </style>
@endsection


@section('content')

    <main class="app-content">
        <!-- The Modal -->
        @include('admin.includes.image_full_screen')

        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    @if(hasPermission('customers.create'))
                        <a href="{{ route('customers.create') }}" class="btn btn-primary" style="float: right;"><i class="fa fa-plus"></i> Create Customer</a>
                    @endif

                    <h3 class="tile-title"><i class="fa fa-list"></i> Customer List </h3>

                    <!-- filter -->
                    <div class="row">
                        <div class="col-sm-8 mx-auto">
                            <form action="" method="get">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="height: 33px !important;" >Search Customer</span>
                                    </div>
                                    <input class="form-control form-control-md"name="search" placeholder="Search anything.........." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button style="cursor: pointer" type="submit" class="input-group-text"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="tile-body table-responsive" style="font-size: 12px;">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                                <tr>
                                    <th class="text-center">Sl.</th>
                                    <th>Customer Code</th>
                                    <th>Name</th>
                                    <th class="text-center">Company&nbsp;Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th class="text-right">Current Balance</th>
                                    <th>Default</th>
                                    <th class="text-center">Images</th>
                                    <th width="13%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($customers as $key => $customer)
                                    <tr>
                                        <td class="text-center">{{ $key + $customers->firstItem() }}</td>
                                        <td>{{ $customer->customerId }}</td>
                                        <td>{{ $customer->name }}</td>
                                        <td class="text-center">{{ $customer->company_name }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>{{ $customer->address }}</td>
                                        <td class="text-right">{{ number_format($customer->current_balance, 2) }}</td>
                                        <td>{!! $customer->default ? '<span class="badge badge-success">Active</span>' : '' !!}</td>
                                        <td class="text-center">
                                            @if ($customer->image)
                                                <div>
                                                    <img width="100" class="full-screen-image" style="cursor: pointer" title="Click on image to fullscreen" height="100" src="{{ asset($customer->image) }}">
                                                </div>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                @include('admin.includes.user-log', ['data' => $customer])

                                                @if(hasPermission('customers.edit'))
                                                    <a class="btn btn-primary btn-sm" title="Edit" href="{{ route('customers.edit', $customer->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                <a class="btn btn-outline-info btn-sm" title="View" href="{{ route('customers.show', $customer->id) }}"> <i class="fa fa-eye"></i></a>

                                                @if(hasPermission('customers.destroy'))
                                                    <a title="Delete Customer" href="#" class="btn btn-danger btn-sm"
                                                       data-toggle="modal" onclick="deleteData({{ $customer->id }})"
                                                       data-target="#DeleteModal"><i class="fa fa-trash"></i></a>
                                                @endif
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            @include('admin.includes.delete_confirm')

                            </tbody>
                        </table>


                        @include('admin.includes.pagination', ['data' => $customers])

                    </div>
                </div>

            </div>
        </div>
    </main>


@endsection


@section('js')
    <!-- Data table plugin-->

    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/sweetalert.min.js')}}"></script>

    <script type="text/javascript">
        function deleteData(id) {
            var id = id;
            var url = '{{ route("customers.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>

    <script type="text/javascript" src="{{ asset('assets/custom_js/image_full_screen.js') }}"></script>
@endsection
