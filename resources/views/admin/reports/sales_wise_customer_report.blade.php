@extends('admin.master')
@section('content')



    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">

                                    <form action="{{ url('reports/sales/customer-report') }}" method="get">
                                        <table class="table table-bordered table-sm table-striped">
                                            <tr>
                                                <td width="30%">
                                                    <label> &nbsp;</label>
                                                    <h3>Sales Customer Reports</h3>
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


                                                <td width="20%" class="no-print">

                                                    <div class="form-group" style="margin-top: 26px;">
                                                        <button class="btn btn-primary"><i
                                                                class="fa fa-check"></i> Check
                                                        </button>
                                                        <a href="{{ url('reports/sales') }}" class="btn btn-danger"><i
                                                                class="fa fa-refresh"></i></a>
                                                        <button type="button" class="btn btn-secondary" onclick="window
                                                    .print()">
                                                            <i class="fa fa-print"></i> Print
                                                        </button>
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
                                <th>Date</th>
                                <th>Customer Name</th>
                                <th class="text-right">Sales Amount</th>
                                <th class="text-right">Collection</th>
                                <th class="text-right">Due</th>
                            </tr>
                            </thead>
                            <tbody>

                            @isset($sales)
                                @php
                                    $totalQuantity = 0;
                                    $totalPrice = 0;
                                @endphp
                                @foreach($sales as $sales_info)
                                    <tr>
                                        <td>{{ $sales_info->sale_date }}</td>
                                        <td>{{ $sales_info->customer->name }}</td>
                                        <td class="text-right">{{ $sales_info->total_payable }}</td>
                                        <td class="text-right">{{ $sales_info->paid_amount }}</td>
                                        <td class="text-right">
                                            @if ($sales_info->total_payable < $sales_info->paid_amount)
                                                <span class="text-success">
                                                    {{ abs($sales_info->total_payable -  $sales_info->paid_amount) }}
                                                </span>
                                            @else
                                                <span class="text-danger">
                                                    {{ $sales_info->total_payable -  $sales_info->paid_amount }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3"></td>
                                    <td>Total Quantity : {{ $totalQuantity }}</td>
                                    <td>Total Price : {{ number_format($totalPrice) }}</td>
                                </tr>
                            @endisset

                            </tbody>
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
    </script>

    @include('admin.includes.date_field')

    @include('admin.includes.delete_confirm')

@endsection
