
@extends('admin.master')
@section('title', ' - Supplier Wise Return Report')

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
                        
                        <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                            <thead>
                                <tr>
                                    <td colspan="9" class="text-center py-2" style="border: none !important;">
                                        @include('partials._print_header')
                                        <h4>Supplier Wise Return Report</h4>
                                        @if (request('from'))
                                            <p>
                                                Showing sales From <b>{{request('from')}}</b>
                                                to <b>{{request('to')}}</b>
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th width="5%">Sl</th>
                                    <th>Sale Reference</th>
                                    <th>Date</th>
                                    <th class="text-right">Total Payable</th>
                                    <th class="text-right">Paid Amount</th>
                                    <th class="text-right">Change</th>
                                </tr>
                            </thead>

                            @if($sale_returns->count() > 0)
                                @php
                                    $total_return = 0;
                                    $total_paid = 0;
                                    $total_change = 0;
                                @endphp
                                <tbody>
                                    @foreach($sale_returns as $key => $sale_return)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $sale_return->sale->sale_reference }}</td>
                                            <td>{{ $sale_return->date->format('d-m-Y') }}</td>
                                            <td class="text-right">{{ $return_amount = $sale_return->amount }}</td>
                                            <td class="text-right">{{ $paid_amount = $sale_return->paid_amount }}</td>
                                            <td class="text-right">{{ $change_amount = $sale_return->change_amount }}</td>
                                        </tr>
                                        @php
                                            $total_return += $return_amount;
                                            $total_paid += $paid_amount;
                                            $total_change += $change_amount;
                                        @endphp
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="3">Total :</th>
                                        <th class="text-right">{{ $total_return }}</th>
                                        <th class="text-right">{{ $total_paid }}</th>
                                        <th class="text-right">{{ $total_change }}</th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
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

        $(document).ready(function(){
            window.print();
        });
    </script>

    @include('admin.includes.date_field')

    @include('admin.includes.delete_confirm')

@endsection
