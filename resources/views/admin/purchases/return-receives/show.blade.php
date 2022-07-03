@extends('admin.master')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Invoice</h1>
                <p>Purchase Return Invoice</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="#">Invoice</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <section class="invoice">
                        <div class="row mb-4">
                            <div class="col-6">
                                <h2 class="page-header"><i class="fa fa-globe"></i>
                                    {{ $purchaseReturnReceive->company->name }}
                                </h2>
                            </div>
                            <div class="col-6">
                                <h4 class="text-right">
                                    <span class="text-secondary">Date :</span>
                                    {{ $purchaseReturnReceive->date }}
                                </h4>
                            </div>
                        </div>

                        <div class="row invoice-info">
                            <div class="col-6">
                                From
                                <address><strong>{{ $purchaseReturnReceive->supplier->name }}</strong><br>
                                    Address : {{ $purchaseReturnReceive->supplier->address }}<br>
                                    Phone : {{ $purchaseReturnReceive->supplier->phone }}<br>
                                    Email: {{ $purchaseReturnReceive->supplier->email }}
                                </address>
                            </div>
                            <div class="col-6 text-right">
{{--                                To--}}
{{--                                <address><strong>{{ $purchaseReturnReceive->company->name }}</strong><br>--}}
{{--                                    Address : {{ $purchaseReturnReceive->company->address }}<br>--}}
{{--                                    Phone : {{ $purchaseReturnReceive->company->phone }}<br>--}}
{{--                                    Email: {{ $purchaseReturnReceive->company->email }}--}}
{{--                                </address>--}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th width="3%">Sl.</th>
                                            <th>Product Code</th>
                                            <th>Product Name</th>
                                            <th>Description</th>
                                            <th>Condition</th>
                                            <th class="text-center" width="30">Quantity</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php $grandTotal = 0 @endphp
                                        @foreach ($purchaseReturnReceive->receive_details as $key => $detail)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ optional($detail->product)->product_code }}</td>
                                                <td>{{ optional($detail->product)->product_name }}</td>
                                                <td>{{ $detail->description }}</td>
                                                <td>{{ $detail->condition ? 'Good' : 'Damaged' }}</td>
                                                <td class="text-center">{{ $detail->quantity }}</td>
                                            </tr>
                                        @endforeach
                                    <tr>
                                        <th colspan="5"><strong>Total :</strong></th>
                                        <th class="text-center">{{ $purchaseReturnReceive->receive_details->sum('quantity') }}</th>
                                    </tr>

                                    @if ($purchaseReturnReceive->comment)
                                        <tr>
                                            <td colspan="5">Comment: {{ $purchaseReturnReceive->comment }}</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row d-print-none mt-2">
                            <div class="col-12 text-right">
                                <a class="btn btn-primary" href="" onclick="window.print()"><i class="fa fa-print"></i> Print</a>
                                <a class="btn btn-info" href="{{ route('purchase-return-receives.index') }}"><i class="fa fa-backward"></i> Back To List</a>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>
@endsection
