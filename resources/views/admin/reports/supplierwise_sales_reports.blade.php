
@extends('admin.master')
@section('title', ' - Supplier Wise Sales Report')

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
                        <div class="row d-print-none">
                            <div class="col-md-11 mx-auto">
                                <div class="table-responsive">

                                    <form action="{{ url('reports/supplier-wise-sales-report') }}" method="get">
                                        <table class="table table-borderless table-sm">
                                            <tr>
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
                                                        <a href="{{ url('reports/supplier-wise-sales-report') }}" class="btn btn-danger"><i
                                                                class="fa fa-refresh"></i></a>
                                                        <button type="button" class="btn btn-success" onclick="window
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

                        <div class="row">
                            <div class="col-sm-11 mx-auto">
                                <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                                    <thead>
                                        <tr>
                                            <td colspan="9" class="text-center py-2" style="border: none !important;">
                                                <h3 class="text-center d-print">{{ optional(auth()->user()->company)->name }}</h3>
                                                    <h4>Supplier Wise Sales Report</h4>
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
                                                <th>Date</th>
                                                <th>Product Name</th>
                                                <th class="text-right">Total Quantity</th>
                                                <th class="text-right">Total Price</th>
                                            </tr>
                                        </thead>
                                    <tbody>

                                    @isset($sales)
                                        @php
                                            $totalQuantity = 0;
                                            $totalPrice = 0;
                                            $sl = $sales->firstItem();
                                        @endphp
                                        @foreach($sales as $sales_info)
                                            @foreach($sales_info->sale_details as $salesProduct)
                                                <tr>
                                                    <td>{{ $sl++}}</td>
                                                    <td>{{ $sales_info->sale_date->format('d-m-Y') }}</td>
                                                    <td>{{ $salesProduct->product->product_name }}</td>
                                                    <td class="text-right">{{ $salesProduct->quantity }}</td>
                                                    <td class="text-right">{{ number_format(($salesProduct->quantity *
                                            $salesProduct->unit_price), 2)
                                            }}</td>
                                                </tr>
                                                @php
                                                    $totalQuantity += $salesProduct->quantity;
                                                    $totalPrice += ($salesProduct->quantity * $salesProduct->unit_price);
                                                @endphp
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <td colspan="3"></td>
                                            <td class="text-right">Total Quantity : {{ $totalQuantity }}</td>
                                            <td class="text-right">Total Price : {{ number_format($totalPrice) }}</td>
                                        </tr>
                                    @endisset

                                    </tbody>
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

    @include('admin.includes.delete_confirm')

@endsection
