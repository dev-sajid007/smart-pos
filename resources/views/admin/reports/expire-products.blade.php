@extends('admin.master')
@section('title', ' - Expire Products Report')

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">

                    <!-- Filter -->
                    <div class="row d-print-none">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <form method="get">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td width="15%">
                                                <label>Category</label>
                                                <select name="fk_category_id" class="form-control select2">
                                                    <option value="">Select</option>
                                                    @foreach ($categories as $id => $name)
                                                        <option value="{{ $id }}" {{ $id == request('fk_category_id') ? 'selected' : '' }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td width="15%">
                                                <label>Supplier</label>
                                                <select name="fk_supplier_id" class="form-control select2">
                                                    <option value="">Select</option>
                                                    @foreach ($suppliers as $id => $name)
                                                        <option value="{{ $id }}" {{ $id == request('fk_supplier_id') ? 'selected' : '' }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td width="20%" class="no-print">
                                                <label class="control-label">For Date</label>
                                                <input type="text" class="form-control dateField" name="date" value="{{ request('date') }}" autocomplete="off">
                                            </td>


                                            <td width="20%" class="no-print">

                                                <div class="form-group" style="margin-top: 26px;">
                                                    <button class="btn btn-primary">
                                                        <i class="fa fa-check"></i> Check
                                                    </button>
                                                    <a href="{{ route('expire.products') }}" class="btn btn-danger"><i class="fa fa-refresh"></i></a>
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

                    <div class="tile-body">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <td colspan="9" class="text-center py-2" style="border: none !important;">
                                            <h4>Expire Products Report</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="1%">Sl</td>
                                        <td>Product Name</td>
                                        <td>Supplier</td>
                                        <td>Category</td>
                                        <td class="text-center">Available Qty</td>
                                        <td class="text-center">Valid Qty</td>
                                        <td class="text-center">Expire Qty</td>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($stocks as $key => $stock)
                                        @php
                                            $availableQty = $stock->available_quantity;
                                            $validQty     = $stock->total_valid_quantity;
                                            $expireQty    = $availableQty - $validQty
                                        @endphp
                                        @if($expireQty > 0)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ optional($stock->product)->product_name }}</td>
                                                <td>{{ optional(optional($stock->product)->supplier)->name }}</td>
                                                <td>{{ optional(optional($stock->product)->category_name)->category_name }}</td>
                                                <td class="available_quantity text-center">{{ $availableQty }}</td>
                                                <td class="available_quantity text-center">{{ $validQty ?? 0 }}</td>
                                                <td class="available_quantity text-center">{{ $expireQty ?? 0 }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('footer-script')
    <script type="text/javascript" src="{{asset('jq/select2Loads.js')}}"></script>
    <script>
        $('.expire-date').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
        });

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
