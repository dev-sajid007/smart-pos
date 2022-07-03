
@extends('admin.master')
@section('title', ' - Receivable Due Report')

@push('style')
    <style type="text/css">
        @media print {
            .d-none {
                display: block !important;
            }
            .d-print {
                display: block !important;
            }
            .d-print-none {
                display: none !important;
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
                            <div class="col-md-11 mx-auto text-right">
                                <button type="button" class="btn btn-success float-right" onclick="window.print()">
                                    <i class="fa fa-print"></i> Print
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-11 mx-auto">
                                <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                                    <thead>
                                        <tr>
                                            <td colspan="9" class="text-center py-2" style="border: none !important;">
                                                @include('partials._print_header')
                                                <h5 class="text-center d-print" style="margin-top: -10px !important;">From {{ date('F d, Y') }}</h5>
                                                <h4>Receivable Due Report</h4>
                                        </tr>

                                        <tr>
                                            <th width="8%">Sl.</th>
                                            <th>Customer Name</th>
                                            <th class="text-right">Receivable Amount</th>
                                        </tr>
                                    </thead>

                                    @php $total = 0; @endphp
                                    @isset($customerBalances)
                                        <tbody>
                                            @foreach ($customers as $key => $customer)
                                                {{-- @php
                                                    $balance = $customerBalances->where('id', $customer->id)->first()['balance'];
                                                    $total += $balance
                                                @endphp --}}
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $customer->name }}</td>
                                                    <td class="text-right receivable_due">{{ number_format($customer->current_balance ?? 0, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <th colspan="2"><strong>Total:</strong></th>
                                                <th class="text-right">{{ number_format($total ?? 0, 2) }}</th>
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
@endsection
