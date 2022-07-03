
@extends('admin.master')
@section('title', ' - Supplier Wise Discount Report')

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
                        <div class="row d-print-none">
                            <div class="col-md-11 mx-auto">
                                <div class="table-responsive">

                                    <form action="" method="get">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="30%">
                                                    <label>Supplier</label>
                                                    <select name="fk_supplier_id" class="form-control select2">
                                                        <option value="">Select</option>
                                                        @foreach ($suppliers as $id => $name)
                                                            <option value="{{ $id }}" {{ $id == request()->fk_supplier_id ? 'selected':'' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td width="20%" class="no-print">
                                                    <label class="control-label">Start Date</label>
                                                    <input type="text" class="form-control dateField" name="from"
                                                           value="{{ request()->get('from') ?? date('Y-m-d') }}"
                                                           autocomplete="off">
                                                </td>

                                                <td width="20%" class="no-print">
                                                    <label class="control-label">End Date</label>
                                                    <input type="text" class="form-control dateField" name="to"
                                                           value="{{ request()->get('to') ?? date('Y-m-d') }}"
                                                           autocomplete="off">
                                                </td>


                                                <td width="20%" class="no-print text-right">

                                                    <div class="form-group" style="margin-top: 26px;">
                                                        <button class="btn btn-primary"><i
                                                                class="fa fa-check"></i> Check
                                                        </button>
                                                        <a href="{{ url('reports/sale_discounts') }}" class="btn btn-danger"><i
                                                                class="fa fa-refresh"></i></a>
                                                        <button type="button" class="btn btn-success" onclick="window
                                                        .print()">
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

                        <div class="row">
                            <div class="col-sm-11 mx-auto">
                                <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                                    <thead>
                                        <tr>
                                            <td colspan="9" class="text-center py-2" style="border: none !important;">
                                                @include('partials._print_header')
                                                <h4>Supplier Wise Discount Report</h4>
                                                @if (request('from'))
                                                    <p>
                                                        Showing sale discounts From <b>{{request('from')}}</b>
                                                        to <b>{{request('to')}}</b>
                                                    </p>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="4%">Sl</th>
                                            <th>Date</th>
                                            <th>Invoice ID </th>
                                            <th class="text-right">Total Price</th>
                                            <th class="text-right">Discount</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @isset($sale_discounts)
                                            @php
                                                $totalPrice = 0;
                                                $totalDiscount = 0;
                                            @endphp
                                            <tbody>
                                                @foreach($sale_discounts as $key => $sale_discounts_info)
                                                    <tr>
                                                        <td>{{ $key+1 }}</td>
                                                        <td>{{ $sale_discounts_info->sale_date->format('d-m-Y') }}</td>
                                                        <td>{{ $sale_discounts_info->invoiceId }}</td>
                                                        <td class="text-right">{{ $sale_discounts_info->total_payable ?? 0 }}</td>
                                                        <td class="text-right">{{ $invoice_discount = $sale_discounts_info->invoice_discount ?? 0 }}</td>
                                                    </tr>
                                                    @php
                                                        $totalPrice += $sale_discounts_info->total_payable;
                                                        $totalDiscount += $invoice_discount;
                                                    @endphp
                                                @endforeach
                                            </tbody>

                                            <tfoot>
                                                <tr class="font-weight-bold">
                                                    <td colspan="3">Total: </td>
                                                    <td class="text-right">{{ number_format($totalPrice, 2) }}</td>
                                                    <td class="text-right">{{ number_format($totalDiscount, 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        @endisset
                                    </tbody>
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

    @include('admin.includes.date_field')
@endsection
