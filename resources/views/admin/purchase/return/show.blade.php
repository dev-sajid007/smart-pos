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
                                    {{ $purchaseReturn->company->name }}
                                </h2>
                            </div>
                            <div class="col-6">
                                <h4 class="text-right">
                                    <span class="text-secondary">Date :</span>
                                    {{ $purchaseReturn->date }}
                                </h4>
                                <h4 class="text-right">
                                    <span class="text-secondary">Invoice Id:</span>
                                    {{ $purchaseReturn->invoice_id }}
                                </h4>
                            </div>
                        </div>

                        <div class="row invoice-info">
                            <div class="col-6">
                                From
                                <address><strong>{{ $purchaseReturn->company->name }}</strong><br>
                                    Address : {{ $purchaseReturn->company->address }}<br>
                                    Phone : {{ $purchaseReturn->company->phone }}<br>
                                    Email: {{ $purchaseReturn->company->email }}
                                </address>
                            </div>
                            <div class="col-6 text-right">To
                                <address><strong>{{ $purchaseReturn->supplier->name }}</strong><br>
                                    Address : {{ $purchaseReturn->supplier->address }}<br>
                                    Phone : {{ $purchaseReturn->supplier->phone }}<br>
                                    Email: {{ $purchaseReturn->supplier->email }}
                                </address>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th width="3%">Sl.</th>
                                            <th>Product Name</th>
                                            <th>Description</th>
                                            <th class="text-right">Price</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-right" width="12%">Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php $grandTotal = 0 @endphp
                                        @foreach ($purchaseReturn->purchaseReturnDetails as $key => $detail)
                                            @php $grandTotal += $total = $detail->amount * $detail->quantity @endphp
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $detail->product->product_name }}</td>
                                                <td>{{ $detail->condition ? 'Good' : 'Damaged' }}</td>
                                                <td class="text-right">{{ number_format($detail->amount, 2) }}</td>
                                                <td class="text-center">{{ $detail->quantity }}</td>
                                                <td class="text-right">{{ number_format($total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>



                                    <tfoot>
                                        <tr>
                                            <th colspan="4" rowspan="10" style="border: none">
                                                Note: {{ $purchaseReturn->comment }}
                                            </th>
                                            <th style="border: none" class="text-right">Total Amount :</th>
                                            <th style="border: none; text-align: right">
                                                {{ number_format($grandTotal, 2) }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="border: none" class="text-right">Previous Due :</th>
                                            <th style="border: none; text-align: right">
                                                {{ number_format($purchaseReturn->previous_due, 2) }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="border: none"  class="text-right">Grand Total :</th>
                                            <th style="border: none; text-align: right">
                                                {{ number_format($purchaseReturn->previous_due - $grandTotal, 2) }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="border: none" class="text-right">Return Amount :</th>
                                            <th style="border: none; text-align: right">
                                                {{ number_format($purchaseReturn->paid_amount, 2) }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="border: none" class="text-right">Current Balance :</th>
                                            <th style="border: none; text-align: right">
                                                {{ number_format($purchaseReturn->previous_due - $grandTotal + $purchaseReturn->paid_amount, 2) }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="row d-print-none mt-2">
                            <div class="col-12 text-right">
                                <div class="btn-group btn-corner">
                                    <a class="btn btn-primary" href="" onclick="window.print()"><i class="fa fa-print"></i> Print</a>
                                    <a class="btn btn-danger" href="{{ route('purchase-returns.index') }}"><i class="fa fa-backward"></i> Back To List</a>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>
@endsection
