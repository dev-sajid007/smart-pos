@extends('admin.master')
@section('title', ' - Daily Report')

@push('style')
    <style type="text/css">
        @media print {
            .d-none {
                display: block !important;
            }
            .d-print {
                display: block !important;
            }
        }
        .d-print {
            display: none;
        }
    </style>
@endpush

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <!-- print header & print button -->
                    <div class="row">
                        <div class="col-sm-12">
                            <button onclick="window.print()" class="btn btn-success no-print mb-2" style="float: right !important;">
                                <i class="fa fa-print"></i> Print
                            </button>
                            @include('partials._print_header')
                        </div>
                    </div>

                    <div class="tile-body">

                        <!-- daily sales report -->
                        <div class="row mt-2">
                            <div style="width: 94% !important; margin-left: 3% !important;">
                                <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                                    <thead>
                                        <tr>
                                            <td colspan="9" class="text-center py-2" style="border: none !important;">
                                                <h5>Daily Sales Report </h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="1%">SL</th>
                                            <th>Product Name</th>
                                            <th width="20%" class="sales text-right" style="padding-right: 10px">Per Unit</th>
                                            <th width="20%" class="sales text-center">Quantity</th>
                                            <th width="20%" class="sales text-right" style="padding-right: 10px">Total Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $totalQty = 0;
                                            $totalAmount = 0;
                                        @endphp
                                        @foreach($todaySales as $key => $product)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $product->product_name }}</td>
                                                <td class="text-right" style="padding-right: 10px">{{ $product->product_price }}</td>
                                                <td class="text-center">{{ $product->todaySoldQuantity() }}</td>
                                                <td class="text-right" style="padding-right: 10px">{{ number_format($product->todaySoldAmount(), 2) }}</td>
                                            </tr>
                                            @php
                                                $totalQty += $product->todaySoldQuantity();
                                                $totalAmount += $product->todaySoldAmount();
                                            @endphp
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th colspan="3"><strong>Total:</strong></th>
                                            <th class="text-center">{{ $totalQty }}</th>
                                            <th class="text-right" style="padding-right: 10px">{{ number_format($totalAmount, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- daily purchase report -->
                        <div class="row" style="margin-top: 20px">
                            <div style="width: 94% !important; margin-left: 3% !important;">
                                <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                                    <thead>
                                        <tr>
                                            <td colspan="9" class="text-center py-2" style="border: none !important;">
                                                <h5>Daily Purchase Report </h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="1%">SL</th>
                                            <th>Product Name</th>
                                            <th width="15%" class="text-right" style="padding-right: 10px">Per Unit</th>
                                            <th width="20%" class="purchase text-center">Quantity</th>
                                            <th width="20%" class="text-right" style="padding-right: 10px">Total Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $totalQty = 0;
                                            $totalAmount = 0;
                                        @endphp
                                        @foreach($todayPurchases as $key => $product)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $product->product_name }}</td>
                                                <td class="text-right" style="padding-right: 10px">{{ $product->product_price }}</td>
                                                <td class="text-center">{{ $product->todayPurchaseQuantity() }}</td>
                                                <td class="text-right" style="padding-right: 10px">{{ number_format($product->todayPurchaseAmount(), 2) }}</td>
                                            </tr>
                                            @php
                                                $totalQty += $product->todayPurchaseQuantity();
                                                $totalAmount += $product->todayPurchaseAmount();
                                            @endphp
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th colspan="3"><strong>Total:</strong></th>
                                            <th class="text-center">{{ $totalQty }}</th>
                                            <th class="text-right" style="padding-right: 10px">{{ number_format($totalAmount, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
