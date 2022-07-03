@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <form action="{{ url('reports/date-product-reports') }}" method="get">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="10%">
                                                    {{--<label class="control-label"></label>--}}
                                                    {{--<h2><i class="fa fa-database"></i> Date Wise Product Report </h2>--}}
                                                </td>
                                                <td width="20%" class="no-print">
                                                    <label class="control-label">Product Name</label>
                                                    <select name="product" id="product"
                                                            class="form-control dynamic_product">
                                                        @foreach($products as $id => $product_name)
                                                            <option
                                                                    {{ request()->get('product') == $id ? 'selected' : '' }} value="{{ $id }}">{{ $product_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('product')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td width="20%" class="no-print">
                                                    <label class="control-label">Date</label>
                                                    <input type="text" id="dateField" class="form-control dateField"
                                                           name="date" value="{{ request()->get('date') }}"
                                                           placeholder="Select Date" autocomplete="off">
                                                    @error('date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td width="15%" class="no-print">

                                                    <div class="form-group" style="margin-top: 26px;">
                                                        <button class="btn btn-primary" title="Check"><i
                                                                    class="fa fa-check"></i></button>
                                                        <a href="{{ url('reports/product_stock') }}"
                                                           class="btn btn-danger" onclick="refresh()" title="Refresh"><i
                                                                    class="fa fa-refresh"></i> </a>
                                                        <button type="button" class="btn btn-success" onclick="window
                                                        .print()"><i
                                                                    class="fa fa-print"></i>Print
                                                        </button>
                                                    </div>


                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            {{--                            </div>--}}

                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="stock_table">
                                <thead>
                                <tr>
                                    <td colspan="9" class="text-center py-2">
                                        <h4>Date Wise Product Report</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <th>Product Name</th>
                                    <th>Product Purchased</th>
                                    <th>Product Sold</th>
                                </tr>
                                </thead>
                                <tbody>

                                @isset($purchaseQuantity)

                                    @php
                                        $product = \App\Product::where('id',request()->get('product'))->select('product_name')->first();
                                    @endphp
                                    <tr>
                                        <td>{{ request()->get('date') }}</td>
                                        <td>{{ $product->product_name }}</td>
                                        <td>{{ $purchaseQuantity->totalQuantity + $purchaseQuantity->totalFreeQuantity  }}</td>
                                        <td>{{ $salesQuantity->totalSealsQuantity ? : 0 }}</td>
                                    </tr>

                                @endisset


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
    <script>

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

    </script>
    @include('admin.includes.date_field')


    <script type="text/javascript" src="{{asset('jq/select2Loads.js')}}"></script>
    <script>
        select2Loads({
            selector: '.dynamic_product',
            url: "/products",
        })

        select2Loads({
            selector: '.supplier',
            url: "/people/suppliers",
        })
    </script>
@stop
