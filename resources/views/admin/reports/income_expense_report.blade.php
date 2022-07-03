@extends('admin.master')
@section('title', ' - Income Expense Report')
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

                        @include('partials._print_header')

                        <!-- Filter -->
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <form >
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td width="10%">
                                            </td>
                                            <td width="20%" class=" no-print">
                                                <label class="control-label">Start Date</label>
                                                <input type="text" class="form-control dateField" name="from" value="{{ request()->get('from') ?? date('Y-m-d') }}" autocomplete="off">
                                            </td>

                                            <td width="20%" class=" no-print">
                                                <label class="control-label">End Date</label>
                                                <input type="text" class="form-control dateField" name="to" value="{{ request()->get('to') ?? date('Y-m-d') }}" autocomplete="off">
                                            </td>


                                            <td width="20%" class=" no-print">
                                                <div class="form-group" style="margin-top: 26px;">
                                                    <button class="btn btn-primary"><i class="fa fa-check"></i> Check </button>
                                                    <a href="{{ url('reports/debit_credit') }}" class="btn btn-danger"><i class="fa fa-refresh"></i></a>
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

                        @isset($reports)
                            <div class="row">
                                <div class="col-md-6 table-responsive">
                                    <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                                        <thead>
                                            <tr>
                                                <td colspan="9" class="text-center py-2" style="border: none !important;">
                                                    <h4>Income List</h4>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Sl.</th>
                                                <th>Chart of Account</th>
                                                <th style="padding-right: 10px; text-align: right">Amount</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php $totalIncome = 0; $i = 2 @endphp
{{--                                            @dd($reports->where('head_type', 0))--}}

                                            <tr>
                                                <td>1</td>
                                                <td>Collection</td>
                                                <td style="padding-right: 10px; text-align: right">{{ number_format($collections ?? 0, 2) }}</td>
                                            </tr>
                                            @foreach ($reports->where('head_type', 0) as $income)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $income->head_name }}</td>
                                                    <td style="padding-right: 10px; text-align: right">{{ number_format($income->transactions->amount ?? 0, 2) }}</td>
                                                </tr>
                                                @php $totalIncome += $income->transactions->amount ?? 0 @endphp
                                            @endforeach
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <th colspan="2"><strong>Total: </strong></th>
                                                <th style="padding-right: 10px; text-align: right">{{ number_format($totalIncome + $collections, 2) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="col-md-6 table-responsive">
                                    <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                                        <thead>
                                            <tr>
                                                <td colspan="9" class="text-center py-2" style="border: none !important;">
                                                    <h4>Expense List</h4>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Sl.</th>
                                                <th>Chart of Account</th>
                                                <th style="padding-right: 10px; text-align: right">Amount</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php $totalExpense = 0; $key = 2; @endphp
                                            <tr>
                                                <td>1</td>
                                                <td>Payment</td>
                                                <td style="padding-right: 10px; text-align: right">{{ number_format(abs($payments) ?? 0, 2) }}</td>
                                            </tr>
                                            @foreach ($reports->where('head_type', 1) as $expense)
                                                <tr>
                                                    <td>{{ $key++ }}</td>
                                                    <td>{{ $expense->head_name }}</td>
                                                    <td style="padding-right: 10px; text-align: right">{{ number_format(abs(optional($expense->transactions)->amount) ?? 0, 2) }}</td>
                                                </tr>
                                                @php $totalExpense += $income->transactions->amount ?? 0 @endphp
                                            @endforeach
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <th colspan="2"><strong>Total: </strong></th>
                                                <th style="padding-right: 10px; text-align: right">{{ number_format($totalExpense, 2) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </main>


    <script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>
    <script type="text/javascript">
        $('.select2').select2();
    </script>
    @include('admin.includes.date_field')
@endsection
