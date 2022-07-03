
@extends('admin.master')
@section('title', ' - Payable Due Report')

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
                            <div class="col-md-11 mx-auto text-right">
                                <button type="button" class="btn btn-success" onclick="window.print()">
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
                                                <h4>Payable Due Report</h4>
                                                @if(request()->supplier_id)
                                                    <p>
                                                        <strong> Supplier: </strong>
                                                        <span style="font-weight:bold">{{ $supplier->name }}
                                                    <br></span>
                                                        {{ $supplier->phone ?? ''}}, {{ $supplier->email ?? ''}}
                                                    </p>
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th width="5%">Sl.</th>
                                            <th>Supplier Name</th>
                                            <th class="text-right">Payable Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($suppliers as $key => $supplier)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $supplier->name }}</td>
                                                <td class="text-right">{{ $supplier->current_balance }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th colspan="2"><strong>Total: </strong></th>
                                            <th class="text-right"><strong>{{ number_format($suppliers->sum('current_balance'), 2) }}</strong></th>
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

    @include('admin.includes.date_field')

    <script type="text/javascript">
     var total_paid_due = 0
        $('.paid_due').each(function(){
            var paid_due = $(this).text() != NaN ? parseFloat($(this).text()):0;
            total_paid_due += paid_due;
        });
        $('#total_paid_due').text(total_paid_due);
    </script>

@endsection

@section('footer-script')
    <script src="{{asset('jq/select2Loads.js')}}"></script>
@stop
