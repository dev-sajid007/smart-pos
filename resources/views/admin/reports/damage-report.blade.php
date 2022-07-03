
@extends('admin.master')
@section('title', ' - Damage Report')

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
                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{ url('reports/damage-report/') }}" method="get">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="no-print" width="10%">
                                                <label class="control-label">Type</label>

                                                <select name="type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="expired" {{ request()->type == 'expired' ? 'selected':'' }}>Expired</option>
                                                    <option value="damaged" {{ request()->type == 'damaged' ? 'selected':'' }}>Damaged</option>
                                                </select>
                                            </td>
                                            <td class="no-print" width="20%">
                                                <label class="control-label">Select Supplier </label>
                                                <select name="supplier_id" class="form-control supplier_id select2">
                                                    <option value="">Select</option>
                                                    @foreach ($suppliers as $id => $name)
                                                        <option value="{{ $id }}" {{ $id == request()->supplier_id ? 'selected':'' }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td width="8%" class="no-print">
                                                <label class="control-label">Start Date</label>
                                                <input type="text" class="form-control dateField" name="from" value="{{ request()->get('from') ?? date('Y-m-d')}}" autocomplete="off">
                                            </td>

                                            <td width="8%" class="no-print">
                                                <label class="control-label">End Date</label>
                                                <input type="text" class="form-control dateField" name="to" value="{{ request()->get('to') ?? date('Y-m-d')}}" autocomplete="off">
                                            </td>


                                            <td width="20%" class="no-print">

                                                <div class="form-group" style="margin-top: 26px;">
                                                    <button class="btn btn-primary"><i class="fa fa-check"></i> Check </button>
                                                    <a href="{{ url('reports/damage-report') }}" class="btn btn-danger"><i class="fa fa-refresh"></i></a>
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

                        <div class="row">
                            <div class="col-sm-11 mx-auto">
                                <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                                    <thead>
                                    <tr>
                                        <td colspan="9" class="text-center py-2" style="border: none !important;">
                                            @include('partials._print_header')
                                            <h4>Damage Report</h4>
                                            @if(request()->supplier_id)
                                                <p>
                                                    <strong> Supplier: </strong>
                                                    <span style="font-weight:bold">{{ $supplier->name }}
                                                    <br></span>
                                                    {{ $supplier->phone ?? ''}}, {{ $supplier->email ?? '' }}
                                                </p>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="8%">Sl.</th>
                                        <th>Supplier Name</th>
                                        <th class="text-right">Quantity</th>
                                        <th class="text-right">Unit Price</th>
                                        <th class="text-right">Sub Total</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @isset($product_damage_items)
                                        @foreach ($product_damage_items as $key => $product_damage_item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $product_damage_item->product->product_name ?? '' }}</td>
                                                <td class="text-right quantity">{{ $quantity = $product_damage_item->quantity ?? 0 }}</td>
                                                <td class="text-right unit_price">{{ $price = optional($product_damage_item->product)->product_price ?? 0 }}</td>
                                                <td class="text-right sub_total">{{ $total = $quantity*$price }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                    <tr class="text-right">
                                        <th colspan="2">Total</th>
                                        <th id="total_quantity"></th>
                                        <th id="total_unit_price"></th>
                                        <th id="total_sub_total"></th>
                                    </tr>
                                    </tfoot>
                                    @endisset
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>

    @include('admin.includes.date_field')

    
    <script type="text/javascript">
    showTotal('unit_price');
    showTotal('quantity');
    showTotal('sub_total');

    function showTotal(field){
        var grand_total = 0
        $('.'+field).each(function(){
            var field_value = $(this).text() != NaN ? parseFloat($(this).text()):0;
            grand_total += field_value;
        });
        $('#total_'+field).text(grand_total);
    }
    </script>
@endsection

@section('footer-script')
    <script src="{{asset('jq/select2Loads.js')}}"></script>
@stop