
@extends('admin.master')
@section('title', ' - Customer Report')

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
                            <div class="col-md-12 no-print">
                                <form action="{{ route('customers.report') }}" method="get">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td width="5%"></td>
                                            <td class="no-print" width="20%">
                                                <label class="control-label">Customer</label>
                                                <select name="customer_id" class="form-control select2">
                                                    <option value="">All</option>
                                                    @foreach($customers as $id => $name)
                                                        <option value="{{ $id }}" {{ $id == request('customer_id') ? 'selected':'' }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="no-print" width="8%">
                                                <label class="control-label">Start Date</label>
                                                <input type="text" class="form-control dateField" name="from" value="{{ request()->get('from') ?? date('Y-m-d') }}" autocomplete="off">
                                            </td>

                                            <td class="no-print" width="8%">
                                                <label class="control-label">End Date</label>
                                                <input type="text" class="form-control dateField" name="to" value="{{ request()->get('to') ?? date('Y-m-d') }}" autocomplete="off">
                                            </td>


                                            <td class="no-print" width="20%">
                                                <div class="form-group" style="margin-top: 26px;">
                                                    <button class="btn btn-primary"><i class="fa fa-check"></i> Check </button>
                                                    <a href="{{ url('reports/customer_report') }}"
                                                       class="btn btn-danger"><i class="fa fa-refresh"></i></a>
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


                        <!-- Customer report data -->
                        <div class="table-responsive">
                            <div class="col-sm-11 mx-auto">
                                <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                                    <thead>
                                        <tr>
                                            <td colspan="7" class="text-center py-2" style="border: none !important;">
                                                @include('partials._print_header')
                                                <h4>Customer Report</h4>
                                                @if (request()->filled('customer_id'))
                                                    <p>
                                                        Customer:
                                                        <span style="font-weight:bold">{{ $customer->name }}
                                                        <br></span>
                                                        {{ $customer->phone }}, {{ $customer->email }}
                                                    </p>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="5%">Sl.</th>
                                            <th width="12%">Date</th>
                                            <th width="12%">Invoice Id</th>
                                            <th>Description</th>
                                            <th width="10%" class="text-right">Debit/Sales</th>
                                            <th width="10%" class="text-right">Credit/Receive</th>
                                            <th class="text-right">Balance</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @isset($transactions)
                                            <tr>
                                                <th colspan="6" class="pl-2">Opening Balance</th>
                                                <th class="text-right">{{ number_format($openingBalance, 2) }}</th>
                                            </tr>


                                            @php $sl = 1 @endphp
                                            @foreach($transactions as $key => $transaction)
                                                <tr>
                                                    <td>{{ $sl++ }}</td>
                                                    <td> {{ $transaction['date'] }} </td>
                                                    <td>
                                                        @if($transaction['type'] == 'Sale')
                                                            <a href="{{ route('sales.show', $transaction['source_id']) }}?print_type=invoice" target="_blank">{{ $transaction['invoice_id'] }}</a>
                                                        @else
                                                            <a href="javascript:void(0)" >{{ $transaction['invoice_id'] }}</a>
                                                        @endif
                                                    </td>
                                                    <td> {{ $transaction['type'] }} {{ ($transaction['discount'] ?? 0) > 0 ? ( ' (' . ($transaction['discount'] ?? 0) . ')') : '' }} </td>
                                                    <td class="text-right">{{ number_format(abs($transaction['debit']), 2) }}</td>
                                                    <td class="text-right">{{ number_format(abs($transaction['credit']), 2) }}</td>
                                                    <td  class="text-right">
                                                        {{ number_format($openingBalance += ($transaction['debit'] - $transaction['credit'] - ($transaction['discount'] ?? 0)), 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endisset
                                    </tbody>
                                </table>
                                <p class="text-muted">
                                    *Positive balance means that amount is showing as customer's due. (
                                    ব্যালেন্স ধনাত্মক হলে তা উক্ত  কাস্টমার  এর  বাকী  টাকা হিসেবে বিবেচিত
                                    হবে এবং ঋণাত্মক হলে জমা)
                                </p>
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

    @include('admin.includes.delete_confirm')

@endsection

