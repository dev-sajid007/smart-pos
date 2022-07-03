
@extends('admin.master')

@section('title', ' - G A Parties Report')

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
                    <div class="no-print">
                        <h3 class="tile-title pull-left">G A Parties Report </h3>

                        <br> <br>
                        <form action="" method="get" style="width: 100%">

                            <div class="row" >
                                <div class="col-sm-3">

                                    <select name="party_id" onchange="this.form.submit()" class="form-control select2 col-3 pull-right">
                                        <option value="">Select</option>
                                        @foreach($parties as $id => $name)
                                            <option value="{{$id}}">{{$name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="from" value="{{ request('from') ?? date('Y-m-d') }}" placeholder="From" class="form-control ml-auto mr-2 dateField">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="to" value="{{ request('to') ?? date('Y-m-d') }}" placeholder="To" class="form-control mr-2 dateField">
                                </div>
                                <div class="col-sm-3">
                                    <button class="btn btn-primary mr-5">
                                        <i class="fa fa-search" aria-hidden="true"></i> Filter
                                    </button>
                                    <button onclick="window.print()" class="btn btn-success no-print" >
                                        <i class="fa fa-print"></i> Print
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>

                    <div class="tile-body">
                        <div class="mt-4 row">
                            <div class="col-sm-11 mx-auto">
                                <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                                    <thead>
                                        <tr>
                                            <td colspan="6" class="text-center py-2" style="border: none !important;">
                                                @include('partials._print_header')

                                                <h4>G A Parties Report</h4>

                                                @if (request('from'))
                                                    <p>
{{--                                                        @if (request('account_id'))--}}
{{--                                                            Bank Account: {{ $accounts[request('account_id')] }} <br>--}}
{{--                                                        @endif--}}
                                                        Showing Result From <b>{{ request('from') }}</b>
                                                        to <b>{{ request('to') }}</b>
                                                    </p>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="1%">SL</th>
                                            <th width="15%">Date</th>
                                            <th width="15%">Reference</th>
                                            <th width="15%" class="text-right">Credit</th>
                                            <th width="15%" class="text-right">Debit</th>
                                            <th width="20%" class="text-right">Balance</th>
                                        </tr>
                                    </thead>

                                    <tbody
                                        @php $balance = 0 @endphp
                                        @foreach($reports as $key => $report)
                                            <tr>
                                                <th>{{ $key + 1 }}</th>
                                                <th>{{ \Carbon\Carbon::parse($report->voucher_date)->format('Y-m-d') }}</th>
                                                <th>{{ $report->voucher_reference }}</th>
                                                @if ($report->voucher_type == 'credit')
                                                    <th class="text-right">{{ number_format($report->total_amount, 2) }}</th>
                                                    <th class="text-right"></th>
                                                    @php $balance += $report->total_amount @endphp
                                                @else
                                                    <th class="text-right"></th>
                                                    <th class="text-right">{{ number_format($report->total_amount, 2) }}</th>
                                                    @php $balance -= $report->total_amount @endphp
                                                @endif
                                                <th class="text-right">{{ number_format($balance, 2) }}</th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('footer-script')
@stop
