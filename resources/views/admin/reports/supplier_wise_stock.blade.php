@extends('admin.master')
@section('title', ' - Supplier Wise Stock Report')

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="table-responsive">
                        <form>
                            <table class="table table-sm no-print table-borderless">
                                <tr>
                                    <td width="10%"></td>
                                    <td width="20%" class="no-print ">
                                        <label class="control-label">Supplier Name</label>
                                        <select name="fk_supplier_id" class="form-control">
                                            <option value="">Select</option>
                                            @foreach ($suppliers as $id => $name)
                                                <option value="{{ $id }}" {{ $id == request()->fk_supplier_id ? 'selected':'' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td width="14%" class="no-print">

                                        <button class="btn btn-primary" style="margin-top: 26px;">
                                            <i class="fa fa-check"></i> Check
                                        </button>
                                        <a class="btn btn-success" style="margin-top:26px;" href="{{ route('supplier.wise.stock.reports.print').'?fk_supplier_id='.request()->fk_supplier_id }}" role="button">
                                            <i class="fa fa-print"></i>Print
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>

                    <div class="tile-body">
                        <div class="table-responsive table-sm">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <td colspan="9" class="text-center py-2" style="border: none !important;">
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
                                        <td>Purchase Quantity</td>
                                        <td>Sold Qty</td>
                                        <td>Damaged Qty</td>
                                        <td>Available Qty</td>
                                        <td class="text-right">Product Cost</td>
                                        <td class="text-right">Value</td>
                                    </tr>
                                </thead>

                                <tbody>
                                @php $total = 0 @endphp
                                    @foreach($stocks as $key => $stock)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ optional($stock->product)->product_name }}</td>
                                            <td>{{ optional(optional($stock->product)->category_name)->category_name }}</td>
                                            <td>
                                                <span data-toggle="tooltip" data-placement="top" data-original-title="{{ $stock->product->supplier->phone ?? '' }}">{{ optional(optional($stock->product)->supplier)->name ?? '' }} &nbsp; <small>({{ $stock->product->supplier->company_name ?? '' }})</small></span>
                                            </td>
                                            <td class="purchased_quantity text-center">{{ $stock->purchased_quantity }}</td>
                                            <td class="sold_quantity text-center">{{ $stock->sold_quantity }}</td>
                                            <td class="wastage_quantity text-center">{{ $stock->wastage_quantity }}</td>
                                            <td class="available_quantity text-center">{{ $stock->available_quantity }}</td>
                                            <td class="product_cost text-right">{{ number_format(optional($stock->stock_product)->product_cost, 2) }}</td>
                                            <span class="total_price" hidden>{{ optional($stock->stock_product)->product_cost }}</span>
                                            <td class="available_quantity_totals text-right">{{ number_format(optional($stock->stock_product)->product_cost * $stock->available_quantity, 2)}}</td>
                                            @php $total += (optional($stock->stock_product)->product_cost * $stock->available_quantity) @endphp
                                        </tr>
                                    @endforeach

                                    <tr class="font-weight-bold">
                                        <td colspan="4" class="text-left">Total This Page</td>
                                        <td id="purchased_quantity" class="text-center"></td>
                                        <td id="sold_quantity" class="text-center"></td>
                                        <td id="wastage_quantity" class="text-center"></td>
                                        <td id="available_quantity" class="text-center"></td>
                                        <td></td>
                                        <td id="total_prices" class="text-right">{{ number_format($total, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="9">Grand Total:</th>
                                        <th class="text-right">{{ number_format($totalProductPrice, 2) }}</th>
                                    </tr>
                                </tbody>
                            </table>
                            @include('admin.includes.pagination', ['data' => $stocks])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('footer-script')
    <script type="text/javascript" src="{{asset('jq/select2Loads.js')}}"></script>
    <script>
        select2Loads({
            selector: '.supplier',
            url: "/people/suppliers",
        });


        getTotal('available_quantity');
        getTotal('purchased_quantity');
        getTotal('sold_quantity');
        getTotal('wastage_quantity');
        getTotal('available_quantity_total');
        getTotal('total_price');

        function getTotal(className){
            var total = 0;
            $('.'+className).each(function(){
                field_value = $(this).text() != NaN ? parseFloat($(this).text()):0;
                total += field_value;
            });

            $('#'+className).text(amountFormat(total));
        }

        function amountFormat(amount) {
            return (amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
        }
    </script>
@endsection
