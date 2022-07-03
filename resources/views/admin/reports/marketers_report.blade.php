@extends('admin.master')
@section('title', ' - Marketers Report')
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
    $count=0;
@endphp
@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <input class="is-add-product-cost" type="hidden" value="{{ request('update_product_cost') }}">
                        <!-- Filter -->
                        <div class="row d-print-none filter-area">
                            <div class="col-md-11 mx-auto">
                                <div class="table-responsive">
                                    <form action="" method="get">
                                        <input type="hidden" name="update_product_cost" value="">

                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="20%">
                                                    <label>Customer</label>
                                                    <select name="marketers_id" class="form-control select2">
                                                        <option value="">All</option>
                                                        @foreach ($marketers as $key => $marketer)
                                                            <option value="{{ $marketer->id }}" {{ $marketer->id == request()->marketers_id ? 'selected':'' }}>
                                                                {{ $marketer->marketers_name??'' }}
                                                            </option>
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
                                {{--<input type="hidden" name="update_product_cost" value="update">
                                    <div class="col-sm-12 text-center">
                                        <span class="text-success">{{ $count }} updated</span>
                                    </div>--}}
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
                                                <h4>Marketers Report</h4>
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
                                            <th width="15%">Sales Date</th>
                                            <th width="15%" style="text-align: center">Invoice ID </th>
                                            <th width="15%">Marketer name</th>
                                            <th class="text-right">Sale Price</th>
                                            <th class="text-right">percantage</th>
                                            <th class="text-right">percantage amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalPrice             = 0;
                                            $totalpercantage_amount = 0;
                                        @endphp

                                        @foreach($sales as $key => $sales_info)
                                            <tr>
                                                <td>{{ $key + 1}}</td>
                                                <td>{{ $sales_info->sale_date->format('Y-m-d') }}</td>
                                                <td style="text-align: center">
                                                    <a href="{{ route('sales.show', $sales_info->id) }}?print_type=invoice" target="_blank">{{ $sales_info->invoiceId }}</a>
                                                </td>
                                                <td>{{ $sales_info->marketers->marketers_name??'' }}</td>
                                                <td class="text-right">{{ number_format($sale = $sales_info->sub_total ?? 0, 2) }}</td>
                                                @php
                                                    $Commission = App\marketers_details::where('marketers_id',$sales_info->marketers_id)
                                                                           ->where('start_amount','<=', $sales_info->sub_total)
                                                                           ->where('end_amount','>=', $sales_info->sub_total)
                                                                           ->first('marketers_commission');
                                                    $sub_total            = $sales_info->sub_total ?? 0;
                                                    $marketers_commission = $Commission->marketers_commission ?? 0;
                                                    $percantage_amount    = $sale = (($sub_total * $marketers_commission)/100) ?? 0;
                                                    $invoice_discount     = $Commission->marketers_commission?? 0;
                                                @endphp
                                                <td class="text-right">{{ number_format($invoice_discount, 2) }}%</td>
                                                <td class="text-right">{{ number_format($percantage_amount , 2) }}</td>
                                            </tr>

                                            @php
                                                $totalPrice             = $totalPrice + $sale;
                                                $totalpercantage_amount = $totalpercantage_amount + $percantage_amount;
                                            @endphp
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr class="font-weight-bold">
                                            <th colspan="4"  class="text-right"><strong>Total: </strong> </th>
                                            <th class="text-right">{{ number_format($totalPrice, 2) }}</th>
                                            <th class="text-right">-</th>
                                            <th class="text-right">{{ number_format($totalpercantage_amount, 2) }}</th>
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
