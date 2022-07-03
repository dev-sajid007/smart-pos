
@extends('admin.master')
@section('title', ' - Tax Report')

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

                        <!-- Filter -->
                        <div class="row d-print-none">
                            <div class="col-md-11 mx-auto">
                                <div class="table-responsive">
                                    <form action="" method="get">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="10%">
                                                    <label>Customer</label>
                                                    <select name="fk_customer_id" class="form-control">
                                                        <option value="">All</option>
                                                        @foreach ($customers as $id => $name)
                                                            <option value="{{ $id }}" {{ $id == request()->fk_customer_id ? 'selected':'' }}>{{ $name }}</option>                                                            
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td width="20%" class="no-print">
                                                    <label class="control-label">Start Date</label>
                                                    <input type="text" class="form-control dateField" name="start_date" value="{{ request()->get('start_date') ?? date('Y-m-d') }}" autocomplete="off">
                                                </td>

                                                <td width="20%" class="no-print">
                                                    <label class="control-label">End Date</label>
                                                    <input type="text" class="form-control dateField" name="end_date" value="{{ request()->get('end_date') ?? date('Y-m-d') }}" autocomplete="off">
                                                </td>


                                                <td width="20%" class="no-print text-right">
                                                    <div class="form-group" style="margin-top: 26px;">
                                                        <button class="btn btn-primary"><i class="fa fa-check"></i> Check </button>
                                                        <a href="{{ url('reports/tax-report') }}" class="btn btn-danger"><i class="fa fa-refresh"></i></a>
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
                        </div>

                        <!-- Sale List -->
                        <div class="row">
                            <div class="col-sm-11 mx-auto">
                                <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                                    <thead>
                                        <tr>
                                            <td colspan="9" class="text-center py-2" style="border: none !important;">
                                                @include('partials._print_header')
                                                <h4>Tax Report</h4>
                                                @if (request('from'))
                                                    <p>
                                                        Showing tax From <b>{{ request('from') }}</b>
                                                        to <b>{{request('to')}}</b>
                                                    </p>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="5%">Sl.</th>
                                            <th width="15%">Date</th>
                                            <th width="25%" style="text-align: center">Invoice ID </th>
                                            <th width="35%">Customer </th>
                                            <th class="text-right">Tax Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php $totalAmount = 0; @endphp
                                        @foreach($sales as $key => $sales_info)
                                            <tr>
                                                <td>{{ $key + 1}}</td>
                                                <td>{{ $sales_info->sale_date->format('Y-m-d') }}</td>
                                                <td style="text-align: center"><a href="{{ url('sales/invoice/' . $sales_info->id) }}" target="_blank">{{ $sales_info->invoiceId }}</a></td>
                                                <td>{{ $sales_info->sale_customer->name }}</td>
                                                <td class="text-right">{{ number_format($sales_info->invoice_tax ?? 0, 2) }}</td>
                                            </tr>
                                            @php $totalAmount += $sales_info->invoice_tax; @endphp
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr class="font-weight-bold">
                                            <th colspan="4"><strong>Total: </strong> </th>
                                            <th class="text-right">{{ number_format($totalAmount, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
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
@endsection
