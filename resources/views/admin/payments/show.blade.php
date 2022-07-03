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
        <div class="app-title">
            <div>
                <h1 class="d-print-none"><i class="fa fa-edit"></i> Show {{ ucfirst($voucher->voucher_type) }} Voucher</h1>
                <p class="d-print-none">Print {{ ucfirst($voucher->voucher_type) }} Voucher</p>

            </div>
            <button class="btn btn-primary" onclick="window.print();"><i class="fa fa-print"></i>
                Print</button>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <section class="invoice">
                        <div class="row mb-4" style="width: 100%">
                            <div class="col-12">
                                <h2 class="page-header d-print-none"><i class="fa fa-globe"></i> {{$company->name}}</h2>
                                @include('partials._print_header')
                                <h3 class="text-center d-print">{{ $voucher->voucher_type == 'debit' ? 'Expense(Debit)' : 'Income(Credit)' }} Voucher</h3>
                                <h5 class="text-center d-print">Date: {{$voucher->voucher_date->format('d-m-Y')}}</h5>
                            </div>
                        </div>
                        <div class="row invoice-info">
{{--                            <div class="col-4">From--}}
{{--                                <address>--}}
{{--                                    <strong>{{$company->name}}</strong><br>--}}
{{--                                    Address: {{$company->address}}<br>--}}
{{--                                    Phone: {{$company->phone}}<br>--}}
{{--                                    Email: {{$company->email}}<br>--}}
{{--                                    Website: {{$company->website}}<br>--}}
{{--                                </address>--}}
{{--                            </div>--}}
                            <div class="col-sm-6">
{{--                                To--}}
                                <address>
                                    <strong>{{$voucher->party->name}}</strong><br>
{{--                                    Code: {{$voucher->party->party_code}}<br>--}}
                                    Address: {{$voucher->party->address}}<br>
                                    Phone: {{$voucher->party->phone}}<br>
                                    Email: {{$voucher->party->email}}<br>
                                </address>
                            </div>

                            <div class="col-sm-4 ml-auto">
                                <b>Invoice #{{ str_pad($voucher->id, 6, '0', STR_PAD_LEFT) }}</b>
                                <br>
                                @if($voucher->cheque_number)
                                    <b>Cheque No {{ $voucher->cheque_number }}</b>
                                @endif
                                <b>Account:</b> {{ $voucher->voucher_payment->account->account_name }}<br>
                                <b>Voucher Reference:</b> {{ $voucher->voucher_reference }}<br>
                                <b>Voucher Date:</b> {{ \Carbon\Carbon::parse($voucher->voucher_date)->format('Y-m-d') }}<br>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th width="1%">#</th>
                                            <th width="25%">Chart of Account</th>
                                            <th>Description</th>
                                            <th width="10%" class="text-right">
                                                @if ($voucher->isDebit())
                                                    Paid
                                                    @else
                                                    Receive
                                                @endif

                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($voucher->voucher_account_charts as $k => $chart)
                                            <tr>
                                                <td>{{++$k}}</td>
                                                <td>{{$chart->account_chart->head_name}}</td>
                                                <td>{{$chart->description}}</td>
                                                <td class="text-right">{{number_format($chart->paid, 2)}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <table class="table table-sm table-borderless">
                                    <thead>
                                        <th width="80%"></th>
                                        <th width="20%"></th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-right" colspan="3">
                                                <b>
                                                    @if ($voucher->isDebit())
                                                        Total Paid :
                                                    @else
                                                        Total Receive :
                                                    @endif
                                                </b>
                                            </td>
                                            <td class="text-right"><b>{{ number_format($voucher->paid, 2)}}</b></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="row mt-5 mb-5">
                                    <div class="col-md-4">
                                        <b> Party : {{ $voucher->party->name }} </b><br>
                                        <hr>
                                        Signature and Date
                                    </div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4 text-right">
                                        <b>Issued By : {{ auth()->user()->company->name }} </b> <br>
                                        <hr>
                                        Signature and Date
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>


@endsection

@section('footer-script')
    <script>
        @if(session('success'))
            setTimeout(() => { window.print() }, 4000)
        @else
            window.print()
        @endif
    </script>
@stop