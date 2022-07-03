@extends('admin.master')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Manage Customer</h1>
                <p>Manage Customer Form</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Customers</li>
                <li class="breadcrumb-item"><a href="#">Show Customer</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile table-responsive">
                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary float-right">Back To List</a>
                    <h3 class="tile-title">Customers Table</h3>
                    <table class="table table-bordered">

                        <tbody>
                        <tr>
                            <th>Customer Code</th>
                            <td>{{ $customer->customer_code }}</td>
                        </tr>
                        <tr>
                            <th>Customer Name</th>
                            <td>{{ $customer->name }}</td>
                        </tr>
                        <tr>
                            <th>Customer Email</th>
                            <td>{{ $customer->email }}</td>
                        </tr>
                        <tr>
                            <th>Customer Phone</th>
                            <td>{{ $customer->phone }}</td>
                        </tr>
                        <tr>
                            <th>Customer Company</th>
                            <td>{{ $customer->company_name }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $customer->address }}</td>
                        </tr>
                        <tr>
                            <th>Customer Category</th>
                            <td>{{ $customer->customer_category->name }}</td>
                        </tr>
                        <tr>
                            <th>Opening Balance</th>
                            <td>{{ number_format($customer->customer_previous_due, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Due Limit</th>
                            <td>{{ number_format($customer->due_limit, 2) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <script>
        function confirmDelete() {
            var cnf = confirm('Are you sure?');
            if (cnf) {
                $('#deleteForm').submit();
                return true;
            } else {
                return false;
            }
        }
    </script>

    <!-- Data table plugin-->
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>

@endsection