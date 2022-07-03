@php
    function getModelValue($model) {
        $models = ['App\Sale', 'App\Purchase', 'App\FundTransfer', 'App\VoucherChartPayment', 'App\AccountReview', 'App\Models\RandomReturn'];
        $values = ['Sale', 'Purchase', 'Fund Transfer', 'Voucher', 'Payment', 'Product Return'];

        $index = array_search($model, $models);
        if($index) {
            return $values[$index];
        } else {
            return $model;
        }
    }





@endphp
@extends('admin.master')
@section('title', ' - Cash Flow Report')

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
                        <h3 class="tile-title pull-left">Cash Report </h3>
                        <button onclick="window.print()" class="btn btn-success no-print" style="float: right;">
                            <i class="fa fa-print"></i> Print
                        </button>

                        <form action="" method="get">
                            <div>
                                <button class="btn btn-primary pull-right mr-5">
                                    <i class="fa fa-search" aria-hidden="true"></i> Filter
                                </button>
                                <select name="account_id" onchange="this.form.submit()" class="form-control account_id col-3 pull-right">
                                    <option value="">Select</option>
                                    @foreach($accounts as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group row" >
                                <input type="text" name="from" value="{{ request('from') ?? date('Y-m-d') }}" placeholder="From" class="form-control col-3 ml-auto mr-2 dateField">
                                <input type="text" name="to" value="{{ request('to') ?? date('Y-m-d') }}" placeholder="To" class="form-control col-3 mr-2 dateField">
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
                                                <h4>Cash Flow Report</h4>
                                                @if (request('from'))
                                                    <p>
                                                        @if (request('account_id'))
                                                            Bank Account: {{ $accounts[request('account_id')] }}
                                                        @else
                                                            All Account
                                                        @endif
                                                            <br>
                                                        Showing transactions From <b>{{request('from')}}</b>
                                                        to <b>{{request('to')}}</b>
                                                    </p>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="1%">SL</th>
                                            <th width="15%">Date</th>
                                            <th width="15%">Description</th>
                                            <th width="15%" class="text-right">Dr. Amount (OUT)</th>
                                            <th width="15%" class="text-right">Cr Amount(IN)</th>
                                            <th width="20%" class="text-right">Balance</th>
                                        </tr>
                                        <tr>
                                            <th colspan="5" >Opening Balance</th>
                                            <th class="text-right">{{ $openingBalance }}</th>
                                        </tr>
                                    </thead>

                                    <tbody
                                        @php  $openingBalance = $openingBalance; @endphp

                                        @if (isset($transactions))
                                            @foreach($transactions as $key => $transaction)
                                                @php  $openingBalance += $transaction->amount; @endphp
                                                <tr>
                                                    <th>{{ $key + 1 }}</th>
                                                    <th>{{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}</th>
                                                    <th>{{ str_replace('App\\', ' ', getModelValue($transaction->transactionable_type)) }}</th>
                                                    <th class="text-right">{{ $transaction->amount < 0 ? number_format(-$transaction->amount, 2) : '' }}</th>
                                                    <th class="text-right">{{ $transaction->amount > 0 ? number_format($transaction->amount, 2) : '' }}</th>
                                                    <th class="text-right">{{ number_format($openingBalance, 2) }}</th>
                                                </tr>
                                            @endforeach
                                        @endif
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
    <script>
        $(() => {
            $('.account_id').val({{request('account_id')}})
        })
    </script>
@stop
