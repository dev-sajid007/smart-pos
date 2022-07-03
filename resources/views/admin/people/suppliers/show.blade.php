@extends('admin.master')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Manage Supplier</h1>
                <p>Manage Supplier Form</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Suppliers</li>
                <li class="breadcrumb-item"><a href="#">Show Supplier</a></li>
            </ul>
        </div>
        <div class="row">

            <div class="col-md-12">
                <div class="tile table-responsive">
                    <h3 class="tile-title">Suppliers Table</h3>
                    <table class="table table-bordered">

                        <tbody>
                        <tr>
                            <th>Supplier Code</th>
                            <td>{{ $supplier->supplier_code }}</td>
                        </tr>
                        <tr>
                            <th>Supplier Name</th>
                            <td>{{ $supplier->name }}</td>
                        </tr>
                        <tr>
                            <th>Supplier Email</th>
                            <td>{{ $supplier->email }}</td>
                        </tr>
                        <tr>
                            <th>Supplier Phone</th>
                            <td>{{ $supplier->phone }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $supplier->address }}</td>
                        </tr>

                        <tr>
                            <th>Opening Due</th>
                            <td>{{ $supplier->opening_due }}</td>
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