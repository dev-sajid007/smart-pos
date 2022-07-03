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
            .border-none {
                border: 0px !important;
            }
        }
        @page {
            margin: 1in;
        }
        .d-print {
            display: none;
        }
        .border-none {
            border: none !important;
        }
    </style>
@endpush

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"> </i> Invoice</h1>
                <p>Company To Company Stock Transfer Invoice</p>
            </div>
            <div class="btn-group btn-corner">
                <a class="btn btn-sm btn-primary" href="javascript:window.print();"><i class="fa fa-print"></i> Print</a>
                <a class="btn btn-sm btn-danger" href="{{ route('company-to-companies.index') }}"><i class=" fa fa-backward"></i> Back</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="tile" style="border: none !important;" >

                    <div class="row">

                        <div class="col-12 text-center">
                            @include('partials._alert_message')
                            <h2 class="page-header">{{ $stock->from_company->name }}</h2>
                            <h6>
                                <span>Phone: {{ $stock->from_company->phone }}</span><br>
                                <span>Email: {{ $stock->from_company->email }}</span><br>
                                <span>Address: {{ $stock->from_company->address }}</span><br>
                            </h6>
                            <h4>Company To Company Stock Transfer</h4>
                        </div>

                        <div class="col-md-6" style="width: 50%; float: left">
                            <strong>Transfer To</strong>
                            <address><strong>{{ optional($stock->to_company)->name }}</strong><br>
                                Phone : {{ optional($stock->to_company)->phone }}<br>
                                Email: {{ optional($stock->to_company)->email }}<br>
                                Address : {{ optional($stock->to_company)->address }}
                            </address>
                        </div>

                        <div class="col-md-6">
                            <h4 class="text-right">
                                <span class="text-secondary">Date :</span> {{ $stock->date }}
                            </h4>
                            <h4 class="text-right">
                                <span class="text-secondary">Transfer ID:</span> {{ $stock->invoice_no }}
                            </h4>
                        </div>
                    </div>


                    <table class="table table-bordered table-sm border-none">
                        <thead>
                        <tr>
                            <th width="4%">SL</th>
                            <th width="40%">Product</th>
                            <th width="40%">Product Code</th>
                            <th width="40%">Product Unit</th>
                            <th width="10%" class="text-center">Quantity</th>
                        </tr>
                        </thead>

                        <tbody>
                            @foreach ($stock->stock_transfer_details as $key => $details)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ optional($details->product)->product_name }}</td>
                                    <td>{{ optional($details->product)->product_code }}</td>
                                    <td>{{ optional(optional($details->product)->product_unit)->name }}</td>
                                    <td class="text-center">{{ $details->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot style="font-weight: bold !important;">
                        <tr >
                            <th colspan="4">Total Quantity :</th>
                            <th class="text-center">{{ $stock->total_quantity }}</th>
                        </tr>

                        </tfoot>
                    </table>

                    @if(!$stock->is_received && $stock->from_company_id != auth()->user()->fk_company_id)
                        <form action="{{ route('company-to-companies.receive', $stock->id) }}" method="POST" style="width: 100%">
                            <div class="row ">
                                @csrf

                                <div class="col-sm-6">
                                    <div class="">
                                        <span class="input-group-text" style="background: transparent; border: none">Warehouse</span>
                                    </div>
                                    <div style="min-width: 250px">
                                        <select name="warehouse_id" class="form-control select-warehouse select2 mr-0" style="width: 100%">
                                            <option value="">Show Room</option>
                                            @foreach($warehouses as $id => $warehouse)
                                                <option value="{{ $id }}" {{ old('warehouse_id') == $id ? 'selected' : '' }}>{{ $warehouse }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <button style="margin-top: 35px !important;" class="btn btn-primary"><i class="fa fa-download"></i> Receive</button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="row mt-5 mb-5">
                            <div style="width: 20% !important; margin-left: 2%; float: left">
                                <b> Issued By : </b><br>
                                <span>{{ ucwords(optional($stock->created_user)->name) }}</span><br>
                                <span>{{ $stock->from_company->name }}</span>
                                <hr style="padding: 0; background: black; margin: 0">
                                Signature and Dates
                            </div>
                            <div style="width: 56% !important;"></div>

                            <div class="text-right" style="width: 20% !important; margin-right: 2%">
                                <b>Received By :  </b> <br>
                                <hr style="padding: 0; background: black; margin: 0; margin-top: 60px !important;">
                                Signature and Date
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

@endsection


@section('js')
    <script type="text/javascript">

        @if(session('success'))
        setTimeout(() => { window.print() }, 3000)
        @else
        // window.print()
        @endif
    </script>
@stop
