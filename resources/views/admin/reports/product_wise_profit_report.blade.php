
@extends('admin.master')
@section('title', ' - Product Wise Profit Report')

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
                    <div class="tile-body">

                        <!-- Filter -->
                        <div class="row d-print-none">
                            <div class="col-md-11 mx-auto">
                                <div class="table-responsive">
                                    <form action="" method="get">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td class="no-print">
                                                    <label class="control-label">Category</label>
                                                    <select class="form-control select2" name="category_id">
                                                        <option value="">Select</option>
                                                        @foreach($categories as $id => $name)
                                                            <option value="{{ $id }}" {{ request('category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="no-print">
                                                    <label class="control-label">Brand</label>
                                                    <select class="form-control select2" name="brand_id">
                                                        <option value="">Select</option>
                                                        @foreach($brands as $id => $name)
                                                            <option value="{{ $id }}" {{ request('brand_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td width="25%" class="no-print">
                                                    <label class="control-label">Product</label>
                                                    <select class="form-control select2" name="product_id">
                                                        <option value="">Select</option>
                                                        @foreach($select_products as $id => $name)
                                                            <option value="{{ $id }}" {{ request('product_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="no-print">
                                                    <label class="control-label">From Date</label>
                                                    <input type="text" class="form-control dateField" name="from" value="{{ request()->get('from') ?? date('Y-m-d') }}" autocomplete="off">
                                                </td>

                                                <td class="no-print">
                                                    <label class="control-label">To Date</label>
                                                    <input type="text" class="form-control dateField" name="to" value="{{ request()->get('to') ?? date('Y-m-d') }}" autocomplete="off">
                                                </td>


                                                <td class="no-print text-right">
                                                    <div class="form-group" style="margin-top: 26px;">
                                                        <button class="btn btn-primary"><i class="fa fa-check"></i> Check </button>
                                                        <a href="{{ route('product.wise.profit.report') }}" class="btn btn-danger"><i class="fa fa-refresh"></i></a>
                                                        <button type="button" class="btn btn-success" onclick="window.print()">
                                                            <i class="fa fa-print"></i> Print
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Sale List -->
                        <div class="row">
                            <div class="col-sm-11 mx-auto">
                                <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                                    <thead>
                                        <tr>
                                            <td colspan="6" class="text-center py-2" style="border: none !important;">
                                                @include('partials._print_header')
                                                <h4>Product Wise Profit Report</h4>
                                                @if (request('from'))
                                                    <p>
                                                        Showing Profit From <b>{{ request('from') }}</b>
                                                        to <b>{{ request('to') }}</b>
                                                    </p>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="5%">Sl.</th>
                                            <th>Product Code </th>
                                            <th>Product Name </th>
                                            <th>Category </th>
                                            <th>Brand </th>
                                            <th class="text-right">Product Cost </th>
                                            <th class="text-right">Sale Amount </th>
                                            <th class="text-right">Profit Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $totalSaleAmount = 0;
                                            $totalCostAmount = 0;
                                        @endphp
                                        @foreach($products as $key => $product)
                                            <tr>
                                                <td>{{ $key + 1}}</td>
                                                <td>{{ $product->product_code }}</td>
                                                <td>{{ $product->product_name }}</td>
                                                <td>{{ $product->category->category_name }}</td>
                                                <td>{{ optional($product->brand)->name }}</td>
                                                <td class="text-right">{{ number_format($product->total_product_cost ?? 0, 2) }}</td>
                                                <td class="text-right">{{ number_format($product->total_sale_price ?? 0, 2) }}</td>
                                                <td class="text-right">{{ number_format($product->total_sale_price - $product->total_product_cost ?? 0, 2) }}</td>
                                            </tr>
                                            @php
                                                $totalSaleAmount += $product->total_sale_price;
                                                $totalCostAmount += $product->total_product_cost;
                                            @endphp
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr class="font-weight-bold">
                                            <th colspan="5"><strong>Total: </strong> </th>
                                            <th class="text-right">{{ number_format($totalCostAmount, 2) }}</th>
                                            <th class="text-right">{{ number_format($totalSaleAmount, 2) }}</th>
                                            <th class="text-right">{{ number_format($totalSaleAmount - $totalCostAmount, 2) }}</th>
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


{{--    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>--}}
{{--    <!-- Data table plugin-->--}}
{{--    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js')}}"></script>--}}
{{--    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap.min.js')}}"></script>--}}

    <script src="{{asset('jq/select2Loads.js')}}"></script>

{{--    @include('admin.includes.date_field')--}}
@endsection
