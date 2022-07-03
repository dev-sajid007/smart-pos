
@extends('admin.master')
@section('title', ' - Supplier Report')

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
                                <form action="{{ url('reports/supplier-reports/') }}" method="get">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td width="10%"></td>
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
                                                <input type="text" class="form-control dateField"
                                                       name="from"
                                                       value="{{ request()->get('from') ?? date('Y-m-d')}}"
                                                       autocomplete="off">
                                            </td>

                                            <td width="8%" class="no-print">
                                                <label class="control-label">End Date</label>
                                                <input type="text" class="form-control dateField"
                                                       name="to" value="{{ request()->get('to') ?? date('Y-m-d')}}"
                                                       autocomplete="off">
                                            </td>


                                            <td width="20%" class="no-print">

                                                <div class="form-group" style="margin-top: 26px;">
                                                    <button class="btn btn-primary"><i
                                                                class="fa fa-check"></i> Check
                                                    </button>
                                                    <a href="{{ url('reports/supplier-reports') }}"
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


                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                                    <thead>
                                    <tr>
                                        <td colspan="9" class="text-center py-2" style="border: none !important;">
                                            @include('partials._print_header')
                                            <h4>Supplier Report</h4>
                                            @if (request()->filled('supplier_id'))
                                                <p>
                                                    Supplier:
                                                    <span style="font-weight:bold">{{ optional(optional($transactions->first())->consumer)->name }}
                                                <br></span>
                                                    {{ optional(optional($transactions->first())->consumer)->phone}}, {{ optional(optional($transactions->first())->consumer)->email }}

                                                </p>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="5%">Sl.</th>
                                        <th width="10%">Date</th>
                                        <th width="12%">Invoice Id</th>
                                        <th>Purpose</th>
                                        <th width="15%" class="text-right">Debit / Purchase</th>
                                        <th width="15%" class="text-right">Credit / Payment</th>
                                        <th class="text-right">Balance</th>
                                    </tr>
                                    </thead>
                                    <tbody>


                                    @isset($transactions)
                                        <tr>
                                            <th colspan="6">Opening Balance: </th>

                                            <th class="text-right">{{ number_format($openingBalance, 2) }}</th>
                                        </tr>
                                        @foreach($transactions->sortByDesc('date') as $key => $transaction)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ \Carbon\Carbon::parse($transaction['date'])->format('Y-m-d') }}</td>
                                                <td>{{ $transaction['invoice_id'] }}</td>
                                                <td>{{ $transaction['type'] }} {{ ($transaction['discount'] ?? 0) > 0 ? ( ' (' .( $transaction['discount'] ?? 0) . ')') : '' }}</td>
                                                <td class="text-right">{{ number_format($transaction['debit'], 2) }}</td>
                                                <td class="text-right">{{ number_format($transaction['credit'], 2) }}</td>
                                                <td  class="text-right">
                                                    {{ number_format($openingBalance -= $transaction['credit'] - $transaction['debit'] + ($transaction['discount'] ?? 0), 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                    </tbody>
                                </table>
                            </div>
                            <p class="text-muted d-print-none">
                                *Positive balance means that amount is showing as due to supplier. (
                                ব্যালেন্স ধনাত্মক হলে তা উক্ত সাপ্লায়ার এর প্রতি বাকী টাকা হিসেবে বিবেচিত
                                হবে  )
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('admin.includes.date_field')

@endsection

@section('footer-script')
    <script src="{{asset('jq/select2Loads.js')}}"></script>
    <script>
        // select2Loads({
        //     selector: '.supplier_id',
        //     url: '/people/suppliers'
        // })
    </script>
@stop
