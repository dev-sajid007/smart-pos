
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
                        <div class="row">
                            <div class="col-md-11 mx-auto">
                                <div class="table-responsive">

                                    <form action="{{ url('reports/sale-returns/') }}" method="get">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="10%">
                                                    <label>Type</label>
                                                    <select name="type" class="form-control change-type">
                                                        <option value="">Select</option>
                                                        <option value="returns" {{ request()->type == 'returns' ? 'selected':'' }}>Return</option>
                                                        <option value="wastages" {{ request()->type == 'wastages' ? 'selected':'' }}>Wastage</option>
                                                    </select>
                                                </td>
                                                <td width="10%">
                                                    <label>Supplier</label>
                                                    <select name="fk_supplier_id" class="form-control">
                                                        <option value="">Select</option>
                                                        @foreach ($suppliers as $id => $name)
                                                            <option value="{{ $id }}" {{ $id == request()->fk_supplier_id ? 'selected':'' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td width="20%" class="no-print">
                                                    <label class="control-label">Start Date</label>
                                                    <input type="text" class="form-control dateField" name="start_date"
                                                           value="{{ request()->get('start_date') ?? date('Y-m-d') }}"
                                                           autocomplete="off">
                                                </td>

                                                <td width="20%" class="no-print">
                                                    <label class="control-label">End Date</label>
                                                    <input type="text" class="form-control dateField" name="end_date"
                                                           value="{{ request()->get('end_date') ?? date('Y-m-d') }}"
                                                           autocomplete="off">
                                                </td>


                                                <td width="20%" class="no-print text-right">

                                                    <div class="form-group" style="margin-top: 26px;">
                                                        <button class="btn btn-primary"><i
                                                                class="fa fa-check"></i> Check
                                                        </button>
                                                        <a href="{{ url('reports/sale-returns') }}" class="btn btn-danger"><i
                                                                class="fa fa-refresh"></i></a>
                                                        <a class="btn btn-success" href="{{ url('reports/print-sale-returns?').request()->getQueryString() }}">
                                                            <i class="fa fa-print"></i> Print
                                                        </a>
                                                    </div>

                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>

                        </div>
                        <table class="table table-bordered table-sm" id="stock_table">
                            <thead>
                            <tr>
                                <td colspan="9" class="text-center py-2">
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
                                <th width="1%">#</th>
                                <th>Sale Reference</th>
                                <th>Date</th>
                                <th class="text-right">Total Payable</th>
                                <th class="text-right">Paid Amount</th>
                                <th class="text-right">Change</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if($sale_returns != [])
                                @php
                                    $sl = 1;
                                    $total_return = 0;
                                    $total_paid = 0;
                                    $total_change = 0;
                                @endphp
                                @foreach($sale_returns as $sale_return)
                                        <tr>
                                            <td>{{ $sl++}}</td>
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
                                <tr>
                                            <th colspan="3" class="text-right">Total :</th>
                                            <th class="text-right">{{ $total_return }}</th>
                                            <th class="text-right">{{ $total_paid }}</th>
                                            <th class="text-right">{{ $total_change }}</th>
                                </tr>
                            @endif

                            </tbody>
                        </table>
                        {{-- @include('admin.includes.pagination', ['data' => $sale_returns]) --}}
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

    @include('admin.includes.delete_confirm')

@endsection
