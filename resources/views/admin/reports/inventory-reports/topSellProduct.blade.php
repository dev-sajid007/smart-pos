
@extends('admin.master')
@section('title', ' - Top Sales Report')

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
@php
    $count = 0;
@endphp
@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <!-- Filter -->
                        <div class="row d-print-none filter-area">
                            <div class="col-md-11 mx-auto">
                                <div class="table-responsive">
                                    <form action="" method="get">
                                        <input type="hidden" name="update_product_cost" value="">

                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="20%">
                                                    <label>Products</label>
                                                    {{-- @dd($selectproducts) --}}
                                                    <select name="fk_product_id" class="form-control select2">
                                                        <option value="">All</option>
                                                        @foreach ($selectproducts as $key => $selectproduct)
                                                            <option value="{{ $selectproduct->id }}" {{ $selectproduct->id == request()->fk_product_id ? 'selected':'' }}>{{ $selectproduct->product_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td width="20%" class="no-print">
                                                    <label class="control-label">Start Date</label>
                                                    <input type="text" class="form-control dateField" name="start_date" value="{{ request()->get('start_date') ?? date('Y-m-d') }}" autocomplete="off">
                                                </td>

                                                <td width="20%" class="no-print">
                                                    <label class="control-label">End Date</label>
                                                    <input type="text" class="form-control dateField" name="end_date" value="{{ request()->get('end_date') ?? date('Y-m-d') }}" autocomplete="off">
                                                </td>


                                                <td width="30%" class="no-print text-right">
                                                    <div class="form-group" style="margin-top: 26px;">
                                                        <button class="btn btn-primary"><i class="fa fa-check"></i> Check </button>
                                                        <a href="{{ url('reports/sales') }}" class="btn btn-danger"><i class="fa fa-refresh"></i></a>
                                                        <button type="button" class="btn btn-success" onclick="window.print()">
                                                            <i class="fa fa-print"></i> Print
                                                        </button>
                                                        @if (auth()->user()->email == 'ceo@smartsoftware.com.bd')
                                                            <button type="button" class="btn btn-warning" onclick="addProductCost(1)">
                                                                <i class="fa fa-gear"></i> Add Product Cost
                                                            </button>
                                                        @endif

                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <form action="" method="get" style="width: 100% !important;" class="add-product-cost-area">
                            <div class="row d-print-none ">
                                <input type="hidden" name="update_product_cost" value="update">
                                <div class="col-sm-12 text-center">
                                    <span class="text-success">{{ $count }} updated</span>
                                </div>

                                <div class="col-sm-4">
                                    <p>From:</p>
                                    <input name="from" type="number" class="form-control" value="{{ request('from') }}">
                                </div>
                                <div class="col-sm-4">
                                    <p>Take:</p>
                                    <input name="count" type="number" class="form-control" value="{{ request('count') }}">
                                </div>

                                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                <div class="col-sm-4">
                                    <p>Action</p>
                                    <button type="submit" class="btn btn-primary">Update Product Cost</button>
                                    <button type="button" onclick="addProductCost(0)" class="btn btn-danger">Cancel</button>
                                </div>
                        </div>
                    </form>

                        <!-- Sale List -->
                        <div class="row">
                            <div class="col-sm-11 mx-auto">
                                <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                                    <thead>
                                        <tr>
                                            <td colspan="9" class="text-center py-2" style="border: none !important;">
                                                @include('partials._print_header')
                                                <h4>Top Sales Report</h4>
                                                For {{ request('fk_product_id') ? optional($selectproducts->where('id', request('fk_product_id'))->first())->name : ' All Products' }}

                                                @if (request('start_date'))
                                                    <p>
                                                        Showing Sales From <b>{{ request('start_date') }}</b>
                                                        to <b>{{ request('end_date') }}</b>
                                                    </p>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="5%">Sl.</th>
                                            <th width="15%" style="text-align: center">Invoice ID </th>
                                            <th width="15%">Sell Date</th>
                                            <th class="text-center">Product Name</th>
                                            <th class="text-right" width="15%">Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- {{ $totalQuantitiesPerProduct->max('totalQuantityPerProduct') }} --}}
                                        {{-- @foreach($totalQuantitiesPerProduct as $key => $tQPP)
                                            @dd($totalQuantitiesPerProduct)
                                            {{ $tQPP->totalQuantityPerProduct }}
                                        @endforeach --}}
                                        @php
                                            $totalQuantity = 0;
                                        @endphp
                                        @foreach($salesDetails as $key => $salesDetails_info)
                                        @php

                                        @endphp
                                            <tr>
                                                <td>{{ $key + 1}}</td>
                                                <td style="text-align: center">
                                                    <a href="{{ url('sales/invoice/' . $salesDetails_info->id) }}" target="_blank">{{ $salesDetails_info->sale->invoiceId }}</a>
                                                </td>
                                                <td>{{ $salesDetails_info->sale->sale_date->format('Y-m-d')??'' }}</td>
                                                <td class="text-center">{{ $salesDetails_info->product->product_name }}</td>
                                                @php
                                                    $totalQuantity += $salesDetails_info->quantity;
                                                @endphp
                                                <td class="text-right"> {{ $salesDetails_info->quantity }} </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr class="font-weight-bold">
                                            {{-- <th colspan="4"><strong>Total: </strong> </th>
                                            <th class="text-right">{{ number_format($totalCost, 2) }}</th>
                                            <th class="text-right">{{ number_format($totalPrice, 2) }}</th>
                                            <th class="text-right">{{ number_format($totalDiscount, 2) }}</th>
                                            <th class="text-right">{{ number_format($totalProfit, 2) }}</th> --}}
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <td class="text-right font-normal">Total Quantity: {{ $totalQuantity }}</td>
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


    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap.min.js')}}"></script>

    <script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>

    <script type="text/javascript">
        $('.filter-area').hide()
        $('.add-product-cost-area').hide()
        function addProductCost(status)
        {
            if (status == 1) {
                $('.filter-area').hide()
                $('.add-product-cost-area').show()
            } else {
                $('.filter-area').show()
                $('.add-product-cost-area').hide()
            }
        }
        $(document).ready(function () {
            if($('.is-add-product-cost').val() == 'update') {
                addProductCost(1)
            } else {
                addProductCost(0)
            }
        })
    </script>
    @include('admin.includes.date_field')
@endsection
