@extends('admin.master')

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
                        <div class="table-sm">
                            <table class="table table-hover table-bordered" style="border: none !important;">
                                <thead style="border: none !important;">
                                    <tr style="border: none !important;">
                                        <td colspan="9" class="text-center py-2" style="border: none !important;">
                                            @include('partials._print_header')
                                            <h4>Supplier Wise Stock Report</h4>
                                            @if ($supplier)
                                                <p>
                                                    Supplier:
                                                    <span style="font-weight:bold">{{$supplier->name ?? '' }} ({{$supplier->company_name ?? '' }})
                                                        <br></span>
                                                    {{$supplier->phone ?? '' }}, {{$supplier->email ?? '' }}
                                                </p>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="1%">Sl</td>
                                        <td>Product Name</td>
                                        <td>Category</td>
                                        <td>Supplier</td>
                                        <td>Available Qty</td>
                                        <td>Purchase Qty</td>
                                        <td>Sold Qty</td>
                                        <td>Damaged Qty</td>
                                        <td class="text-right">Product Cost</td>
                                        <td class="text-right">Value</td>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $total_available_quantity = 0;
                                        $total_purchase_quantity = 0;
                                        $total_sold_quantity = 0;
                                        $total_damage_quantity = 0;
                                        $total_amount = 0;
                                    @endphp
                                    @foreach($stocks as $key => $stock)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $stock->product->product_name }}</td>
                                            <td>{{ $stock->product->category_name->category_name }}</td>
                                            <td><span data-toggle="tooltip" data-placement="top" title=""
                                                      data-original-title="{{ $stock->product->supplier_name->phone ?? '' }}">{{ $stock->product->supplier_name->name ??'' }} &nbsp; <small>({{ $stock->product->supplier_name->company_name ?? '' }})</small></span>
                                            </td>
                                            <td class="text-center">{{ $stock->available_quantity }}</td>
                                            <td class="text-center">{{ $stock->purchased_quantity }}</td>
                                            <td class="text-center">{{ $stock->sold_quantity }}</td>
                                            <td class="text-center">{{ $stock->wastage_quantity }}</td>
                                            <td class="text-right">{{ number_format($stock->stock_product->product_cost, 2) }}</td>
                                            <td class="text-right">{{ number_format($stock->stock_product->product_cost *
                                            $stock->available_quantity, 2) }}</td>
                                        </tr>

                                        @php
                                            $total_available_quantity += $stock->available_quantity;
                                            $total_purchase_quantity += $stock->purchased_quantity;
                                            $total_sold_quantity += $stock->sold_quantity;
                                            $total_damage_quantity += $stock->wastage_quantity;
                                            $total_amount += $stock->stock_product->product_cost *
                                            $stock->available_quantity;
                                        @endphp
                                    @endforeach
                                    <tr class="font-weight-bold">
                                        <td colspan="4" class="text-left">Grand Total</td>
                                        <td class="text-center">{{ $total_available_quantity }}</td>
                                        <td class="text-center">{{ $total_purchase_quantity }}</td>
                                        <td class="text-center">{{ $total_sold_quantity }}</td>
                                        <td class="text-center">{{ $total_damage_quantity }}</td>
                                        <td></td>
                                        <td class="text-right">{{ number_format($total_amount, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('footer-script')
    <script type="text/javascript" src="{{ asset('jq/select2Loads.js') }}"></script>
    <script>
        select2Loads({
            selector: '.supplier',
            url: "/people/suppliers",
        });

        $(document).ready(function(){
            window.print();
        });

    </script>
@endsection
