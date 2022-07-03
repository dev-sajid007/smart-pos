@extends('admin.master')

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
                    <div class="tile-header">
                        @include('partials._print_header')
                        <h3 class="text-center">Profit Loss Report</h3>
                        <hr>
                    </div>
                    <div class="tile-body">
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <form >
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td width="10%">
                                            </td>
                                            <td width="20%" class=" no-print">
                                                <label class="control-label">Start Date</label>
                                                <input type="text" class="form-control dateField" name="from"
                                                       value="{{ request()->get('from') ?? date('Y-m-d') }}"
                                                       autocomplete="off">
                                            </td>

                                            <td width="20%" class=" no-print">
                                                <label class="control-label">End Date</label>
                                                <input type="text" class="form-control dateField" name="to"
                                                       value="{{ request()->get('to') ?? date('Y-m-d') }}"
                                                       autocomplete="off">
                                            </td>


                                            <td width="20%" class=" no-print">
                                                <div class="form-group" style="margin-top: 26px;">
                                                    <button class="btn btn-primary">
                                                        <i class="fa fa-check"></i> Filter
                                                    </button>
                                                    <a href="{{ url('reports/customer_report') }}" class="btn btn-danger">
                                                        <i class="fa fa-refresh"></i>
                                                    </a>
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
                            <div class="col-sm-12 table-responsive">
                                <table class="table table-bordered table-sm" id="stock_table">
                                    <thead>
                                        <tr>
                                            <th>Sl.</th>
                                            <th>Description</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Total Purchase Product Price in Stock</td>
                                            <td>{{ $totalPurchaseProductPriceInStock }}</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Total Sale Product Price in Stock</td>
                                            <td>{{ $totalSaleProductPriceInStock }}</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Total Opening Product Price</td>
                                            <td>{{ $totalOpeningProductValue }}</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Total Purchase Price</td>
                                            <td>{{ $totalPurchasePrice }}</td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Total Sale Price</td>
                                            <td>{{ $totalSalePrice }}</td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>Total Sale Profit</td>
                                            <td>{{ $totalSaleProfit = $totalSalePrice - $totalPurchasePrice }}</td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>Supplier Payable Due</td>
                                            <td>{{ $supplierPayableDue }}</td>
                                        </tr>

                                        <tr>
                                            <td>8</td>
                                            <td>Customer Receivable Due</td>
                                            <td>{{ $customerReceivableDue }}</td>
                                        </tr>

                                        <tr>
                                            <td>9</td>
                                            <td>Gl Account Expense</td>
                                            <td>{{ abs($glAccountExpense) }}</td>
                                        </tr>

                                        <tr>
                                            <td>10</td>
                                            <td>Gl Account Asset</td>
                                            <td>{{ abs($glAccountAsset) }}</td>
                                        </tr>
                                        @foreach($accountTransactions as $key => $account)
                                            <tr>
                                                <td>{{ $key + 11 }}</td>
                                                <td>{{ $account->account_name }}</td>
                                                <td>{{ $account->total_amount }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <table class="table table-bordered">
                                    <tr>
                                        <td width="15%">Gross Profit</td>
                                        <td>{{ $totalSaleProfit - ( abs($glAccountExpense + $glAccountAsset + $totalPurchaseProductPriceInStock) ) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Net Profit</td>
                                        <td>{{ $totalSaleProfit - ( abs($glAccountExpense) ) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

{{--                        <div class="row">--}}
{{--                            <div class="col-md-6 table-responsive">--}}
{{--                                <table class="table table-bordered table-sm" id="stock_table">--}}
{{--                                    <thead>--}}
{{--                                        <tr>--}}
{{--                                            <td colspan="9" class="text-center py-2">--}}
{{--                                                <h4>Sale - Purchase</h4>--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <th>Particular</th>--}}
{{--                                            <th>Amount</th>--}}
{{--                                        </tr>--}}
{{--                                    </thead>--}}

{{--                                    <tbody>--}}
{{--                                        <tr>--}}
{{--                                            <td>Sale</td>--}}
{{--                                            <td>{{ $collections }}</td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Purchase</td>--}}
{{--                                            <td>{{ $payments }}</td>--}}
{{--                                        </tr>--}}
{{--                                        <tr class="text-{{ $collections - $payments >= 0 ? 'success' : 'danger' }}">--}}
{{--                                            <th class="text-right">Total {{ $collections - $payments >= 0 ? 'Profit' : 'Loss' }}</th>--}}
{{--                                            <th>{{ abs($collections - $payments) }}</th>--}}
{{--                                        </tr>--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-6 table-responsive">--}}
{{--                                <table class="table table-bordered table-sm" id="stock_table">--}}
{{--                                    <thead>--}}
{{--                                        <tr>--}}
{{--                                            <td colspan="9" class="text-center py-2">--}}
{{--                                                <h4>Income - Expense</h4>--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <th>Particular</th>--}}
{{--                                            <th>Amount</th>--}}
{{--                                        </tr>--}}
{{--                                    </thead>--}}

{{--                                    <tbody>--}}
{{--                                        <tr>--}}
{{--                                            <td>Income</td>--}}
{{--                                            <td>{{ $incomes }}</td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Expense</td>--}}
{{--                                            <td>{{ $expenses }}</td>--}}
{{--                                        </tr>--}}
{{--                                        <tr class="text-{!! $incomes - $expenses  >= 0 ? 'success' : 'danger' !!}">--}}
{{--                                            <th class="text-right">Total {{ $incomes - $expenses >= 0 ? 'Profit' : 'Loss' }}</th>--}}
{{--                                            <th>{{ abs($incomes - $expenses) }}</th>--}}
{{--                                        </tr>--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}
{{--                            <div class="col-12 text-center">--}}
{{--                                @php--}}
{{--                                    $net_profit = ($collections - $payments) + ($incomes - $expenses)--}}
{{--                                @endphp--}}
{{--                                <h3 class="text-{!! $net_profit >= 0 ? 'success' : 'danger' !!}">Net {!! $net_profit > 0 ? "Profit : " : "Loss : " !!}--}}
{{--                                {{ abs($net_profit) }}</h3>--}}
{{--                            </div>--}}
                        </div>
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
