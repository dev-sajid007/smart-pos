@extends('admin.master')
@section('title', ' - Marketers Report')
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
@php
    $count=0;
@endphp
@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <!-- Filter -->
                        <div class="row d-print-none filter-area">
                            <div class="col-md-11 mx-auto">
                                <div class="table-responsive">
                                    <form action="" method="get">
                                        <input type="hidden" name="update_product_cost" value="">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="20%">
                                                    <label>Customer</label>
                                                    <select name="marketers_id" class="form-control select2">
                                                        <option value="">All</option>
                                                        @foreach ($marketers as $key => $marketer)
                                                            <option value="{{ $marketer->id }}" {{ $marketer->id == request()->marketers_id ? 'selected':'' }}>
                                                                {{ $marketer->marketers_name??'' }}
                                                            </option>
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
                                                <td width="30%" class="no-print text-right">
                                                    <div class="form-group" style="margin-top: 26px;">
                                                        <button class="btn btn-primary"><i class="fa fa-check"></i> Check </button>
                                                        <a href="{{ url('reports/sales') }}" class="btn btn-danger"><i class="fa fa-refresh"></i></a>
                                                        <button type="button" class="btn btn-success" onclick="window.print()">
                                                            <i class="fa fa-print"></i> Print
                                                        </button>
                                                        @if (auth()->user()->email == 'ceo@smartsoftware.com.bd')
                                                            <button type="button" class="btn btn-warning" onclick="addProductCost(1)">
                                                                <i class="fa fa-gear"></i> Add Product Cost
                                                            </button>
                                                        @endif

                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Marketers List -->
                        <div class="row">
                            <div class="col-sm-11 mx-auto">
                                <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                                    <thead>
                                        <tr>
                                            <td colspan="9" class="text-center py-2" style="border: none !important;">
                                                @include('partials._print_header')
                                                <h4>Marketers Ledger Report</h4>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="5%">Sl.</th>
                                            <th width="15%" class="text-center">Amount&nbsp;Given Date</th>
                                            <th >Marketer&nbsp;name</th>
                                            <th width="15%" class="text-center">Amount</th>
                                            <th width="15%" class="text-center">Amount&nbsp;Update Date</th>
                                            <th width="5%" class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($marketersLedgerDatas as $key => $marketersLedgerData)
                                            <tr>
                                                <td>{{ $key + 1}}</td>
                                                <td class="text-center">{{ $marketersLedgerData->created_at->format('Y-m-d') }}</td>
                                                <td>{{ $marketersLedgerData->marketers->marketers_name??'' }}</td>
                                                <td class="text-center">{{ number_format($marketersLedgerData->amount ?? 0, 2) }}</td>
                                                <td class="text-center">{{ $marketersLedgerData->updated_at->format('Y-m-d') }}</td>
                                                <td class="text-right">
                                                    <a class="btn btn-primary btn-sm" title="Edit"
                                                        href="{{ route('marketers.ledger.edit',$marketersLedgerData->id) }}">
                                                    <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="110" class="text-right">{{ $marketersLedgerDatas->links() }}</td>
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

        $('.filter-area').hide()
        $('.add-product-cost-area').hide()

        function addProductCost(status)
        {
            if (status == 1) {
                $('.filter-area').hide()
                $('.add-product-cost-area').show()
            } else {
                $('.filter-area').show()
                $('.add-product-cost-area').hide()
            }
        }

        $(document).ready(function () {
            if($('.is-add-product-cost').val() == 'update') {
                addProductCost(1)
            } else {
                addProductCost(0)
            }
        })
    </script>
    @include('admin.includes.date_field')
@endsection
