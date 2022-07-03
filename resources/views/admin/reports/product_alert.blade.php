@extends('admin.master')
@section('title', ' - Product Alert Report')

@push('style')
    <style type="text/css">
        @media print {
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
                    <button onclick="window.print()" class="btn btn-success no-print" style="float: right;">
                        <i class="fa fa-print"></i> Print
                    </button>
                    <h3 class="tile-title d-print-none text-primary"><i class="fa fa-database"></i> Product Stock Alert Report </h3>
                    @include('partials._print_header')
                    <h3 class="text-center d-print" style="margin-bottom: 20px">Product Stock Alert Report</h3>

                    <div class="tile-body">
                        <table class="table table-hover table-bordered table-sm" id="">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Product Code</th>
                                    <th>Product Name</th>
                                    <th>Supplier Name</th>
                                    <th>Supplier Mobile</th>
                                    <th class="text-center">Available Quantity</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($product_stocks as $key => $product_stock)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $product_stock->stock_product->product_code }}</td>
                                        <td>{{ $product_stock->stock_product->product_name }}</td>
                                        <td>{{ optional(optional($product_stock->stock_product)->supplier)->name }}</td>
                                        <td>{{ optional(optional($product_stock->stock_product)->supplier)->phone }}</td>
                                        <td class="text-center"><b class="text-danger">{{ $product_stock->available_quantity }}</b></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
