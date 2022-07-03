@extends('admin.master')

@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-th-list"></i> Supplier Information</h1>
                <p>Supplier information</p>
            </div>

            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Supplier Information</li>
                <li class="breadcrumb-item active"><a href="#">Supplier Information Table</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
               @include('partials._alert_message')

                <div class="tile">
                    <h3 class="tile-title">Supplier List
                        <a href="{{ route('confirm-list-all-supplier.delete') }}" class="btn btn-danger float-right">Delete All</a>
                    </h3>
                    <div class="tile-body" style="min-height: 750px">
                        <form action="{{ route('supplier-confirm-list.store') }}" method="post" style="width: 100%">
                            @csrf

                            <div class="form-group">
                                <div class="">
                                    <button class="btn btn-primary" type="submit" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Import First 50 Supplier</button>
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
                                        <th class="text-right">Opening Balance</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($suppliers as $key => $supplier)
                                        <tr>
                                            <td>{{ $key + $suppliers->firstItem() }}</td>
                                            <td>{{ $supplier->name }}</td>
                                            <td>{{ $supplier->email }}</td>
                                            <td>{{ $supplier->phone }}</td>
                                            <td>{{ $supplier->address }}</td>
                                            <td class="text-right">{{ $supplier->opening_due }}</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm" title="Delete" onclick="delete_item('{{ route('confirm-list-supplier.delete', $supplier->id) }}')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </form>
                        @include('admin.includes.pagination', ['data' => $suppliers])

                        <br>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
        </div>

        <!-- delete form -->
        @include('partials._delete_form')
    </main>

@endsection


@section('footer-script')
{{--    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>--}}
@endsection
