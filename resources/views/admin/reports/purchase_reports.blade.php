
@extends('admin.master')
@section('title', ' - Purchase Report')

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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <form action="{{ url('reports/purchase/') }}" method="get">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="10%"></td>
                                                <td width="20%" class="no-print">
                                                    <label class="control-label">Start Date</label>
                                                    <input type="text" class="form-control dateField" name="from" value="{{ request()->get('from') ?? date('Y-m-d') }}" autocomplete="off">
                                                </td>

                                                <td width="20%" class="no-print">
                                                    <label class="control-label">End Date</label>
                                                    <input type="text" class="form-control dateField" name="to" value="{{ request()->get('to') ?? date('Y-m-d') }}" autocomplete="off">
                                                </td>

                                                <td width="20%" class="no-print">
                                                    <div class="form-group" style="margin-top: 26px;">
                                                        <button class="btn btn-primary"><i class="fa fa-check"></i> Check </button>
                                                        <a href="{{ url('reports/purchase') }}" class="btn btn-danger"><i class="fa fa-refresh"></i></a>
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


                        <!-- Purchase List -->
                        <div class="row">
                            <div class="col-sm-11 mx-auto">
                                <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                                    <thead>
                                    <tr>
                                        <td colspan="9" class="text-center py-2" style="border: none !important;">
                                            @include('partials._print_header')
                                            <h4>Purchase Report</h4>
                                                @if (request('from'))
                                                    <p>
                                                        Showing purchases From <b>{{request('from')}}</b>
                                                        to <b>{{request('to')}}</b>
                                                    </p>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="5%">Sl.</th>
                                            <th width="15%">Date</th>
                                            <th width="15%" style="text-align: center">Invoice ID </th>
                                            <th width="15%">Supplier </th>
                                            <th class="text-right">Total Price</th>
                                            <th class="text-right">Discount</th>
                                            <th class="text-right">Net Price</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $totalPrice = 0;
                                            $totalDiscount = 0;
                                        @endphp

                                        @foreach($purchases as $key => $purchase)
                                            <tr>
                                                <td>{{ $key + 1}}</td>
                                                <td>{{ $purchase->purchase_date->format('Y-m-d') }}</td>
                                                <td style="text-align: center"><a href="{{ url('purchases/invoice/' . $purchase->id) }}" target="_blank">{{ $purchase->invoiceId }}</a></td>
                                                <td>{{ $purchase->purchase_supplier->name }}</td>
                                                <td class="text-right">{{ number_format($purchase->total_purchase_price ?? 0, 2) }}</td>
                                                <td class="text-right">{{ number_format($invoice_discount = $purchase->invoice_discount ?? 0, 2) }}</td>
                                                <td class="text-right">{{ number_format($purchase->total_purchase_price - $invoice_discount, 2) }}</td>
                                            </tr>
                                            @php
                                                $totalPrice += $purchase->total_purchase_price;
                                                $totalDiscount += $invoice_discount;
                                            @endphp
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr class="font-weight-bold">
                                            <th colspan="4"><strong>Total: </strong> </th>
                                            <th class="text-right">{{ number_format($totalPrice, 2) }}</th>
                                            <th class="text-right">{{ number_format($totalDiscount, 2) }}</th>
                                            <th class="text-right">{{ number_format($totalPrice - $totalDiscount, 2) }}</th>
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
        $('.select2').select2();
    </script>
    @include('admin.includes.date_field')
@endsection
