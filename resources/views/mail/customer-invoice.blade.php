<!DOCTYPE html>

@php
    $settings = App\SoftwareSetting::companies()->get();
    $has_additional_item_field  = optional($settings->where('title', 'Need Additional Item Id Field')->first())->options == 'need-additional-item-id-field';
    $invoiceBarcode             = optional($settings->where('title', 'Show Barcode in Invoice')->first())->options == 'yes';
    $col_span                   = $has_additional_item_field ? 5 : 4;
@endphp

<html lang="en">
    <head>
        <title>Invoice Report</title>
        <style type="text/css">
            table {
                border-collapse: collapse;
            }

            table, th, td {
                border: 1px solid black;
            }
            th, td {
                padding: 5px;
            }


        </style>
    </head>

    <body>
        <div class="container" style="width: 96%; margin-left: 2%;">
            <div class="row" id="printDiv">
                <div class="col-md-12">
                    <div class="tile" style="border: 0 !important;">
    
                        <div class="row">
                            {{-- <div class="col-md-12"> --}}
                                <div style="text-align: center;" class="col-12">
                                    @if (file_exists($data->purchase_company->company_logo))
                                        <img height="100" width="100" src="{{ asset($data->purchase_company->company_logo) }}"
                                            style="float: left">
                                    @endif
                                    @if ($data->purchase_company->name == 'Aziz Auto')
                                        <h2 style="font-size: 35px !important; color: #f6931b;" class="page-header">
                                            {{ $data->purchase_company->name }}</h2>
                                    @else
                                        <h2 class="page-header">{{ $data->purchase_company->name }}</h2>
                                    @endif
                                    <h6>
                                        <span>Phone: {{ $data->purchase_company->phone }}</span><br>
                                        <span>Email: {{ $data->purchase_company->email }}</span><br>
                                        <span>{{ $data->purchase_company->address }}</span><br>
                                    </h6>
                                </div>
                                {{-- <h3 class="text-left">{{ $data->purchase_company->name }}</h3>
                                <img style="height: 50px; width: 50px;" src="{{ asset($data->purchase_company->company_logo) }}" alt=""> --}}
                            {{-- </div> --}}
                            {{-- <div class="col-md-6">
                                
                            </div> --}}
                        </div>
    
                        <div class="row mt-5" style="display: flex;">
                            <div class="col-md-6" style="width: 50%; float: left">
                                From
                                <address>
                                    <strong>{{ optional($data->purchase_supplier)->name }}</strong><br>
                                    Address : {{ optional($data->purchase_supplier)->address }}<br>
                                    Phone : {{ optional($data->purchase_supplier)->phone }}<br>
                                    Email: {{ optional($data->purchase_supplier)->email }}
                                </address>
                            </div>
                            <div class="col-md-6" style="width: 50%; text-align: right;">
                                <h2 style="color: blue; text-align: right;">INVOICE</h2>
                                <address>
                                    <h4 class="text-right">
                                        <span class="text-secondary">Date :</span>
                                        {{ $data->purchase_date->format('d/m/Y') }}
                                    </h4>
                                    <h4 class="text-right">
                                        <span class="text-secondary">Invoice ID:</span>
                                        {{ $data->invoiceId }}
                                    </h4>
                                    {{-- <strong>{{ optional($data->purchase_company)->name }}</strong><br>
                                    Address : {{ optional($data->purchase_company)->address }}<br>
                                    Phone : {{ optional($data->purchase_company)->phone }}<br>
                                    Email: {{ optional($data->purchase_company)->email }} --}}
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
                                @foreach ($data->purchase_details as $key => $details)
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
                                    <th style="border: 0 !important; padding: 0 !important;">{{ $data->sub_total }}</th>
                                </tr>
                                <tr>
                                    <th style="border: 0 !important; padding: 0 !important;" colspan="4"></th>
                                    <th style="border: 0 !important; padding: 0 !important;">Discount :</th>
                                    <th style="border: 0 !important; padding: 0 !important;">{{ $data->invoice_discount }}</th>
                                </tr>
                                <tr>
                                    <th style="border: 0 !important; padding: 0 !important;" colspan="4"></th>
                                    <th style="border: 0 !important; padding: 0 !important;">Tax :</th>
                                    <th style="border: 0 !important; padding: 0 !important;">{{ $data->invoice_tax }}</th>
                                </tr>
                                <tr>
                                    <th style="border: 0 !important; padding: 0 !important;" colspan="4"></th>
                                    <th style="border: 0 !important; padding: 0 !important;">Payable Amount :</th>
                                    <th style="border: 0 !important; padding: 0 !important;">{{ $data->invoiceAmount }}</th>
                                </tr>
                                @if ($data->advancedAmount())
                                    <tr>
                                        <th style="border: 0 !important; padding: 0 !important;" colspan="4"></th>
                                        <th style="border: 0 !important; padding: 0 !important;">Advanced :</th>
                                        <th style="border: 0 !important; padding: 0 !important;">{{ $data->advancedAmount() }}</th>
                                    </tr>
                                @else
                                    <tr>
                                        <th style="border: 0 !important; padding: 0 !important;" colspan="4"></th>
                                        <th style="border: 0 !important; padding: 0 !important;">Previous Due :</th>
                                        <th style="border: 0 !important; padding: 0 !important;">{{ $data->previousDue() }}</th>
                                    </tr>
                                @endif
                                <tr>
                                    <th style="border: 0 !important; padding: 0 !important;" colspan="4"></th>
                                    <th style="border: 0 !important; padding: 0 !important;">Total Payable :</th>
                                    <th style="border: 0 !important; padding: 0 !important;">{{ $data->totalPayableAmount() }}</th>
                                </tr>
                                <tr>
                                    <th style="border: 0 !important; padding: 0 !important;" colspan="4"></th>
                                    <th style="border: 0 !important; padding: 0 !important;">Paid :</th>
                                    <th style="border: 0 !important; padding: 0 !important;">{{ $data->isApproved() ? number_format($data->paid, 2) : 'Not Approved Yet' }}</th>
                                </tr>
                                @if ($data->due > 0)
                                    <tr>
                                        <th style="border: 0 !important;" colspan="4"></th>
                                        <th style="border: 0 !important;">Due :</th>
                                        <th style="border: 0 !important; padding: 0">{{ $data->due }}</th>
                                    </tr>
                                @else
                                    <tr>
                                        <th style="border: 0 !important; padding: 0 !important;" colspan="4"></th>
                                        <th style="border: 0 !important; padding: 0 !important;">Available Balance :</th>
                                        <th style="border: 0 !important; padding: 0 !important;">{{ number_format(abs($data->due), 2) }}</th>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
    
    
                        <div class="row mt-5 mb-5" style="width: 100%; display:flex;">
                            <div class="col-md-4" style="width: 33%">
                                <b> Supplier : {{ $data->purchase_supplier->name }} </b><br>
                                <hr>
                                Signature and Date
                            </div>
                            <div class="col-md-4" style="width: 33%"></div>
                            <div style="text-align: right; width: 33% !important" class="col-md-4 text-right">
                                <b>Issued By : {{ $data->purchase_company->name }} </b> <br>
                                <hr>
                                Signature and Date
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
