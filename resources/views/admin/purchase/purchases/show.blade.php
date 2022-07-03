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
        @page {
            margin: 1in;
        }
        .d-print {
            display: none;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #848a90 !important;
        }
    </style>
@endpush

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"> </i> Invoice</h1>
                <p>Purchase Invoice</p>
            </div>
            <div>
                <form action="{{ route('email.send.purchase',$purchase->id) }}" method="post" style="display: inline-block">
                    @csrf
               <input type="hidden" name="type" value="purchase">
               <button class="btn btn-sm btn-info" type="submit"><i class="fa fa-envelope"></i> Email</button>
                </form>
                <a class="btn btn-primary" href="javascript:window.print();"><i class="fa fa-print"></i> Print</a>
                <a class="btn btn-danger" href="{{ route('purchases.index') }}"><i class=" fa fa-backward"></i> Back To
                    List</a>
            </div>

        </div>

        <div class="row" id="printDiv">
            <div class="col-md-12">
                <div class="tile" style="border: 0 !important;">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-12 text-center">
                                @if (file_exists($purchase->purchase_company->company_logo))
                                    <img height="100" width="100" src="{{ asset($purchase->purchase_company->company_logo) }}"
                                        style="float: left">
                                @endif
                                @if ($purchase->purchase_company->name == 'Aziz Auto')
                                    <h2 style="font-size: 35px !important; color: #f6931b;" class="page-header">
                                        {{ $purchase->purchase_company->name }}</h2>
                                @else
                                    <h2 class="page-header">{{ $purchase->purchase_company->name }}</h2>
                                @endif
                                <h6>
                                    <span>Phone: {{ $purchase->purchase_company->phone }}</span><br>
                                    <span>Email: {{ $purchase->purchase_company->email }}</span><br>
                                    <span>{{ $purchase->purchase_company->address }}</span><br>
                                </h6>
                            </div>
                            {{-- <h3 class="text-left">{{ $purchase->purchase_company->name }}</h3>
                            <img style="height: 50px; width: 50px;" src="{{ asset($purchase->purchase_company->company_logo) }}" alt=""> --}}
                        </div>
                        {{-- <div class="col-md-6">
                            
                        </div> --}}
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-6" style="width: 50%; float: left">
                            From
                            <address>
                                <strong>{{ optional($purchase->purchase_supplier)->name }}</strong><br>
                                Address : {{ optional($purchase->purchase_supplier)->address }}<br>
                                Phone : {{ optional($purchase->purchase_supplier)->phone }}<br>
                                Email: {{ optional($purchase->purchase_supplier)->email }}
                            </address>
                        </div>
                        <div class="col-md-6 text-right" style="width: 50%">
                            <h2 class="text-right" style="color: blue">INVOICE</h2>
                            <address>
                                <h4 class="text-right">
                                    <span class="text-secondary">Date :</span>
                                    {{ $purchase->purchase_date->format('d/m/Y') }}
                                </h4>
                                <h4 class="text-right">
                                    <span class="text-secondary">Invoice ID:</span>
                                    {{ $purchase->invoiceId }}
                                </h4>
                                {{-- <strong>{{ optional($purchase->purchase_company)->name }}</strong><br>
                                Address : {{ optional($purchase->purchase_company)->address }}<br>
                                Phone : {{ optional($purchase->purchase_company)->phone }}<br>
                                Email: {{ optional($purchase->purchase_company)->email }} --}}
                            </address>
                        </div>
                    </div>


                    <table class="table table-bordered table-sm border-none" style="border: 0 !important;">
                        <thead>
                            <tr>
                                <th width="1%">SL</th>
                                <th width="40%">Item</th>
                                <th width="10%">Qty</th>
                                <th width="10%">Free Qty</th>
                                <th width="8%" class="text-right">Rate</th>
                                <th width="6%" class="text-right">Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($purchase->purchase_details as $key => $details)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>
                                        {{ $details->product->product_name }}
                                        @if (count($details->serials) > 0)
                                           <br>
                                            <p style="color: #2c65b4; font-size: 13px; margin: 0;word-break: break-all;">Serials No: {{ $details->serials->pluck('serial') }}</p> 
                                        @endif
                                        
                                    </td>
                                    <td>{{ $details->quantity . ' ' . $details->product->product_unit->name }}</td>
                                    <td>{{ $details->free_quantity . ' '. $details->product->product_unit->name }}</td>
                                    <td class="text-right">{{ $details->unit_price }}</td>
                                    <td class="text-right">{{ $details->product_sub_total }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot style="font-weight: bold !important;" class="text-right">
                            <tr style="border: 0 !important;">
                                <th style="border: 0 !important; padding: 0 !important;" colspan="4"></th>
                                <th style="border: 0 !important; padding: 0 !important;">Subtotal :</th>
                                <th style="border: 0 !important; padding: 0 !important;">{{ $purchase->sub_total }}</th>
                            </tr>
                            <tr>
                                <th style="border: 0 !important; padding: 0 !important;" colspan="4"></th>
                                <th style="border: 0 !important; padding: 0 !important;">Discount :</th>
                                <th style="border: 0 !important; padding: 0 !important;">{{ $purchase->invoice_discount }}</th>
                            </tr>
                            <tr>
                                <th style="border: 0 !important; padding: 0 !important;" colspan="4"></th>
                                <th style="border: 0 !important; padding: 0 !important;">Tax :</th>
                                <th style="border: 0 !important; padding: 0 !important;">{{ $purchase->invoice_tax }}</th>
                            </tr>
                            <tr>
                                <th style="border: 0 !important; padding: 0 !important;" colspan="4"></th>
                                <th style="border: 0 !important; padding: 0 !important;">Payable Amount :</th>
                                <th style="border: 0 !important; padding: 0 !important;">{{ $purchase->invoiceAmount }}</th>
                            </tr>
                            @if ($purchase->advancedAmount())
                                <tr>
                                    <th style="border: 0 !important; padding: 0 !important;" colspan="4"></th>
                                    <th style="border: 0 !important; padding: 0 !important;">Advanced :</th>
                                    <th style="border: 0 !important; padding: 0 !important;">{{ $purchase->advancedAmount() }}</th>
                                </tr>
                            @else
                                <tr>
                                    <th style="border: 0 !important; padding: 0 !important;" colspan="4"></th>
                                    <th style="border: 0 !important; padding: 0 !important;">Previous Due :</th>
                                    <th style="border: 0 !important; padding: 0 !important;">{{ $purchase->previousDue() }}</th>
                                </tr>
                            @endif
                            <tr>
                                <th style="border: 0 !important; padding: 0 !important;" colspan="4"></th>
                                <th style="border: 0 !important; padding: 0 !important;">Total Payable :</th>
                                <th style="border: 0 !important; padding: 0 !important;">{{ $purchase->totalPayableAmount() }}</th>
                            </tr>
                            <tr>
                                <th style="border: 0 !important; padding: 0 !important;" colspan="4"></th>
                                <th style="border: 0 !important; padding: 0 !important;">Paid :</th>
                                <th style="border: 0 !important; padding: 0 !important;">{{ $purchase->isApproved() ? number_format($purchase->paid, 2) : 'Not Approved Yet' }}</th>
                            </tr>
                            @if ($purchase->due > 0)
                                <tr>
                                    <th style="border: 0 !important;" colspan="4"></th>
                                    <th style="border: 0 !important;">Due :</th>
                                    <th style="border: 0 !important; padding: 0">{{ $purchase->due }}</th>
                                </tr>
                            @else
                                <tr>
                                    <th style="border: 0 !important; padding: 0 !important;" colspan="4"></th>
                                    <th style="border: 0 !important; padding: 0 !important;">Available Balance :</th>
                                    <th style="border: 0 !important; padding: 0 !important;">{{ number_format(abs($purchase->due), 2) }}</th>
                                </tr>
                            @endif
                        </tfoot>
                    </table>


                    <div class="row mt-5 mb-5" style="width: 100%">
                        <div class="col-md-4" style="width: 33%">
                            <b> Supplier : {{ $purchase->purchase_supplier->name }} </b><br>
                            <hr>
                            Signature and Date
                        </div>
                        <div class="col-md-4" style="width: 33%"></div>
                        <div class="col-md-4 text-right" style="width: 33%">
                            <b>Issued By : {{ $purchase->purchase_company->name }} </b> <br>
                            <hr>
                            Signature and Date
                        </div>
                    </div>
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
