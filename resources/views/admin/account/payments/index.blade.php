@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">

                    @if(hasPermission('payments-method.create'))
                        <a href="{{ route('payments-method.create') }}" class="btn btn-primary" style="float: right;"><i
                                class="fa fa-plus"></i> Add Payment Method</a>
                    @endif
                    <h3><i class="fa fa-th-list"></i> Payment Method List</h3>

                    <div class="tile-body table-responsive">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                                <th width="5%$">#</th>
                                <th>Payment Method</th>
                                <th width="10%" class="text-center">Status</th>
                                <th width="15%" class="text-center">Action</th>
                            </thead>

                            <tbody>
                                @foreach($payments as $key => $payment)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ ucwords($payment->method_name) }}</td>
                                        <td width="10%" class="text-center">
                                            @if($payment->status == 1)
                                                <a href="{{ route('payment.status', $payment->id) }}" title="Do De Active" class="btn btn-success btn-sm">Active</a>
                                            @else
                                                <a href="{{ route('payment.status', $payment->id) }}" title="Do Active" class="btn btn-warning btn-sm">Inactive</a>
                                            @endif
                                        </td>
                                        <td width="15%" class="text-center">
                                            <div class="btn-group btn-corner">
                                                @include('admin.includes.user-log', ['data' => $payment])

                                                @if(hasPermission('payments-method.edit'))
                                                    <a class="btn btn-success btn-sm text-light" title="Edit" href="{{ route('payments-method.edit', $payment->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if(hasPermission('payments-method.destroy'))
                                                    <button type="button" class="btn btn-danger btn-sm" title="Delete" onclick="delete_item('{{ route('accounts.destroy', $payment->id) }}')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- delete form -->
    @include('partials._delete_form')
@endsection

@section('footer-script')
@endsection
