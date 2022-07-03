@extends('admin.master')

@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Manage Software Payment</h1>
                <p>Manage Software Payment Form</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Companies</li>
                <li class="breadcrumb-item"><a href="#">Manage Software Payment</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if (Session::get('message'))
                    <div class="alert alert-success">
                        {{ Session::get('message') }}
                    </div>
                @endif
                @if (Session::get('error_message'))
                    <div class="alert alert-danger">
                        {{ Session::get('error_message') }}
                    </div>
                @endif
                <div class="tile">
                    <h3 class="tile-title">Software Payment List
                        <span class="">
                            <a href="{{ route('software_payments.create') }}" class="btn btn-light px-2 ml-2">+
                                <span style="font-size:16px; font-weight:400;">Add New</span>
                            </a>
                        </span>
                    </h3>
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <td>SL</td>
                                    {{-- <th>Software Name</th> --}}
                                    <th>Amount</th>
                                    <th>Alert Threshold</th>
                                    <td>End Date</td>
                                    <td>Paid Date</td>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($software_payments as $k => $software_payment)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        {{-- <td>{{ $software_payment->company->name }}</td> --}}
                                        <td>{{ $software_payment->amount }}</td>
                                        <td>{{ $software_payment->alert }}</td>
                                        <td>{{ $software_payment->software_payment_date }}</td>
                                        <td>{{ $software_payment->paid_date }}</td>
                                        <td>
                                            {!! $software_payment->status == 1 ? '<span class="badge badge-success">Approved</span>' : '<span class="badge badge-danger">Pending</span>' !!}
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" title="View"
                                                href="{{ url('software_payments/invoice/' . $software_payment->id) }}"> <i
                                                    class="fa fa-eye"></i> </a>
                                            @if ($software_payment->status != 1)
                                                <a class="btn btn-info btn-sm" title="Edit"
                                                    href="{{ route('software_payments.edit', $software_payment->id) }}"> <i
                                                        class="fa fa-edit"></i> </a>
                                                <button class="btn btn-danger btn-sm" title="Delete"
                                                    onclick="delete_item('{{ route('software_payments.destroy', $software_payment->id) }}')"
                                                    type="button">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @include('admin.includes.pagination', ['data' => $software_payments])
                    </div>

                </div>
            </div>
        </div>
    </main>

    <form action="" id="deleteItemForm" method="POST">
        @csrf @method("DELETE")
    </form>

    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>

    <script>
        function formSubmit(id) {
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this data !",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel plz!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {
                if (isConfirm) {
                    $('#deleteForm_' + id).submit();
                    swal("Deleted!", "Your data has been deleted.", "success");
                } else {
                    swal("Cancelled", "Your data is safe :)", "error");
                }
            });
        }
        if (result.value) {
            // $('deleteItemForm').attr({'action': url});
            $('#deleteItemForm').attr('action', ).submit();
        }

    </script>
@endsection
