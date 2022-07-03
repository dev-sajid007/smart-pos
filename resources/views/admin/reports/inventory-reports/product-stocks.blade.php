
@extends('admin.master')
@section('title', ' - Product Wise Stock Report')

@push('style')
    <style>
        @media print {
            tr {
                page-break-inside: avoid;
            }
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
                        <div class="row">
                            <div class="col-md-12 mx-auto">

                                <div class="table-responsive">
                                    <form action="" method="get">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="20%" class="no-print ">
                                                    <label class="control-label">Brand</label>
                                                    <select name="brand_id" class="form-control select2">
                                                        <option value=""> All Brand</option>
                                                        @foreach ($brands as $id => $name)
                                                            <option value="{{ $id }}" {{ request('brand_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td width="20%" class="no-print ">
                                                    <label class="control-label">Category</label>
                                                    <select name="category_id" id="fk_category_id" class="form-control select2">
                                                        <option value=""> All Category</option>
                                                        @foreach ($categories as $key => $category)
                                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                @if ($settings->where('title', 'Product Subcategory')->where('options', 'yes')->count() > 0)
                                                    <td width="20%" class="no-print ">
                                                        <label class="control-label">Sub Category</label>
                                                        <select name="fk_sub_category_id" id="fk_sub_category_id" class="form-control select2">
                                                            <option value="">Select</option>
                                                            @php $subcategories = optional($categories->where('id', request('category_id'))->first())->subcategories ?? [] @endphp
                                                            @foreach ($subcategories as $subcategory)
                                                                <option {{ request('fk_sub_category_id') == $subcategory->id ? 'selected' : ''  }} value="{{ $subcategory->id }}">{{ $subcategory->sub_category_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                @endif

                                                <td width="20%" class="no-print ">
                                                    <label class="control-label">Warehouse</label>
                                                    <select name="warehouse_id" class="form-control select2">
                                                        <option value="" > Showroom</option>
                                                        <option value="all" {{ request('warehouse_id') == 'all' ? 'selected' : '' }}> All Stock</option>
                                                        @foreach ($warehouses as $id => $name)
                                                            <option value="{{ $id }}" {{ request('warehouse_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td width="20%" class="no-print ">
                                                    <label class="control-label">Product Name</label>
                                                    <select name="product_id" class="form-control select2">
                                                        <option value="" > All </option>
                                                        @foreach ($selectproducts as $id => $name)
                                                            <option value="{{ $id }}" {{ request('product_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                @if ($settings->where('title', 'Product Subcategory')->where('options', 'yes')->count() < 1)
                                                    <td width="10px" class="no-print ">
                                                        <div class="btn-group btn-corner">
                                                            <button class="btn btn-primary" style="margin-top: 26px;"> <i class="fa fa-check"></i> Check </button>
                                                            <button type="button" class="btn btn-success" style="margin-top:26px;" onclick="window.print()">
                                                                <i class="fa fa-print"></i> Print
                                                            </button>
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>

                                            @if ($settings->where('title', 'Product Subcategory')->where('options', 'yes')->count() > 0)
                                                <tr>
                                                    <td width="10px" class="no-print text-right" colspan="5">
                                                        <div class="btn-group btn-corner">
                                                            <button class="btn btn-primary" style="margin-top: 26px;"> <i class="fa fa-check"></i> Check </button>
                                                            <button type="button" class="btn btn-success" style="margin-top:26px;" onclick="window.print()">
                                                                <i class="fa fa-print"></i> Print
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div style="width: 90% !important; margin-left: 5%">
                                <table class="table table-bordered table-sm" style="border: none !important;">
                                    <thead style="border: none !important;">
                                        <tr>
                                            <th colspan="9" class="text-center py-2" style="border: none !important;">
                                                @include('partials._print_header')
                                                <h4>Product Wise Stock Report</h4>
                                                <h5>
                                                    @php $showCount = 0 @endphp
                                                    @if (request('brand_id') != '')
                                                        Brand: {{ $brands[request('brand_id')] }}

                                                        @php $showCount++ @endphp
                                                    @endif

                                                    @if (request('category_id') != '')
                                                        @if($showCount > 0) 
                                                            ,
                                                        @endif
                                                        Category: {{ $categories->where('id', request('category_id'))->first()->category_name }}

                                                        @php $showCount++ @endphp
                                                    @endif

                                                    @if (request('fk_sub_category_id') != '')
                                                        @if($showCount > 0) 
                                                            ,
                                                        @endif
                                                        
                                                        Subcategory: {{ optional(optional($subcategories)->where('id', request('fk_sub_category_id'))->first())->sub_category_name }}

                                                        @php $showCount++ @endphp
                                                    @endif

                                                    @if (request('warehouse_id') != '' && request('warehouse_id') != 'all')
                                                        @if($showCount > 0) 
                                                            ,
                                                        @endif
                                                        Warehouse: {{ $warehouses[request('warehouse_id')] }}

                                                        @php $showCount++ @endphp
                                                    @endif
                                                    
                                                </h5>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Sl.</th>
                                            <th width="14%">Product Code</th>
                                            <th>Product Name</th>
                                            <th width="10%" class="text-center">Opening Qty</th>
                                            <th width="10%" class="text-center">Purchased Qty</th>
                                            <th width="10%" class="text-center">Sold Qty</th>
                                            <th width="10%" class="text-center">Available Qty</th>
                                            <th width="10%" class="text-right">Product Cost</th>
                                            <th width="10%" class="text-right">Value</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $total_opening_quantity     = 0;
                                            $total_purchase_quantity    = 0;
                                            $total_sold_quantity        = 0;
                                            $total_available_quantity   = 0;
                                            $subtotal_price             = 0;
                                        @endphp
                                        @foreach ($products as $key => $product)
                                            @php
                                                $total_opening_quantity += $opening_quantity = $product->opening_quantity ?? 0;
                                                $total_purchase_quantity += $purchase_quantity = $product->purchased_quantity ?? 0;
                                                $total_sold_quantity += $sold_quantity = $product->sold_quantity ?? 0;
                                                $total_available_quantity += $available_quantity = ($product->available_quantity ?? 0);
                                                $subtotal_price += $subtotal = $product->product_cost * $available_quantity;
                                            @endphp
                                            
                                            <tr>
                                                <td>{{ $key + $products->firstItem() }}</td>
                                                <td>{{ $product->product_code }}</td>
                                                <td>{{ $product->product_name }}</td>
                                                <td class="text-center">{{ $opening_quantity }}</td>
                                                <td class="text-center">{{ $purchase_quantity }}</td>
                                                <td class="text-center">{{ $sold_quantity }}</td>
                                                <td class="text-center" >
                                                    @if ($product->has_serial == 1 && $available_quantity > 0)
                                                        <a href="{{ route('serial-product.stocks') }}?product_id={{ $product->id }}&warehouse_id={{ request('warehouse_id') }}">{{ $available_quantity }}</a>
                                                    @else
                                                        {{ $available_quantity }}
                                                    @endif
                                                </td>
                                                <td class="text-right">{{ number_format($product->product_cost, 2) }}</td>
                                                <td class="text-right">{{ number_format($subtotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Total this page:</th>
                                            <th class="text-center">{{ $total_opening_quantity }}</th>
                                            <th class="text-center">{{ $total_purchase_quantity }}</th>
                                            <th class="text-center">{{ $total_sold_quantity }}</th>
                                            <th class="text-center">{{ $total_available_quantity }}</th>
                                            <th></th>
                                            <th class="text-right">{{ number_format($subtotal_price, 2) }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="8">Grand Total:</th>
                                            <th class="text-right">{{ number_format($total_product_price, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>

                                @include('admin.includes.pagination', ['data' => $products])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <input type="hidden" class="subcategory-url" value="{{ route('subcategories.index') }}">
@endsection


@section('footer-script')
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js')}}"></script>

    <script type="text/javascript">
        $('.select2').select2();

        $("#submitBtn").on('click', function () {

            $('#stock_table tbody').empty();
            var product_name = $("#product_name").val();
            var purchase_date = $("#purchase_date").val();
            var sale_date = $("#sale_date").val();
            var url_data = 'id=' + product_name + '&purchase=' + purchase_date + '&sale=' + sale_date;
            $.ajax({

                url: "{{ url('reports/get_json_stock') }}",
                type: "GET",
                data: {id: product_name, purchase_date: purchase_date, sale_date: sale_date},
                success: function (res) {
                    $.each(res, function (key, value) {
                        var total_sold = 0;
                        if (value['sales_details'] != null) {
                            total_sold = value['sales_details']['total_sold'];
                        }
                        var tr = '<tr>' +
                            '<td>' + value['product']['product_code'] + '</td>' +
                            '<td>' + value['product']['product_name'] + '</td>' +
                            '<td>' + value['total_purchased'] + '</td>' +
                            '<td>' + total_sold + '</td>' +
                            '<td>' + value['product_stock']['available_quantity'] + '</td>' +
                            '</tr>';
                        $('#stock_table tbody').append(tr);
                    });
                }
            });
        });


        $('#fk_category_id').change(function () {

            var categoryId = $(this).val();
            if (categoryId) {

                $.ajax({
                    type: "GET",
                    url: $('.subcategory-url').val() + '/' + categoryId,
                    success: function (res) {
                        if (res) {
                            $("#fk_sub_category_id").empty();
                            $("#fk_sub_category_id").append('<option value="">Salect One</option>');
                            $(res).each(function (index, item) {
                                $("#fk_sub_category_id").append('<option value="' + item.id + '">' + item.sub_category_name + '</option>');
                            });
                        } else {
                            $("#fk_sub_category_id").empty();
                        }

                    }
                });
            } else {
                $("#fk_sub_category_id").empty();
            }
        });
    </script>
    
    @include('admin.includes.date_field')

    <script type="text/javascript" src="{{asset('jq/select2Loads.js')}}"></script>
    <script>
        select2Loads({
            selector: '.dynamic_product',
            url: "{{ url('product/products') }}",
        })
    </script>
@endsection
