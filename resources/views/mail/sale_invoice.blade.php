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
            <div class="row mb-4">
                <div class="col-12 text-center" style="text-align: center">
                    <h2 style="margin-bottom: 0">{{ optional($data->sale_company)->name }}</h2>
                    <h6 style="font-size: 12px !important; margin-top: 0 !important;">
                        <span>Phone: {{ optional($data->sale_company)->phone }}</span><br>
                        <span>Email: {{ optional($data->sale_company)->email }}</span><br>
                        <span>Address: {{ optional($data->sale_company)->address }}</span><br>
                    </h6>
                </div>
            </div>

            <div class="row invoice-info" style="width: 100%">
                <div class="col-6" style="width: 50%; float: left">
                    <strong>Bill To</strong>
                    <address><strong>{{ optional($data->sale_customer)->name }}</strong><br>
                        Address : {{ optional($data->sale_customer)->address }}<br>
                        Phone : {{ optional($data->sale_customer)->phone }}<br>
                        Email: {{ optional($data->sale_customer)->email }}
                        @if ($data->currier_id != '')
                            <br>
                            Courier: {{ optional($data->currier)->name }}
                        @endif
                    </address>
                </div>

                <div class="col-6" style="width: 50%; float: right; text-align: right">
                    <h2 style="color: blue; margin-bottom: 0">INVOICE</h2>
                    <h4 style="margin-top: 0; margin-bottom: 0">
                        <span>Date :</span>
                        {{ fdate($data->sale_date, 'Y-m-d') }}
                    </h4>
                    <h4 style="margin-top: 0">
                        <span class="text-secondary">Invoice ID:</span>
                        {{ $data->invoiceId }}
                    </h4>
                </div>
            </div>

            <table style="width: 100%; border: none; border-collapse: collapse">
                <thead>
                    <tr>
                        <th style="width:2%; border:1px solid black; text-align: left !important;">Sl.</th>
                        <th class="text-center" style="border: 1px solid black; text-align: left !important;">Product Name</th>
                        <th style="border: 1px solid black; text-align: left !important;">Unit</th>
                        @if($has_additional_item_field)
                            <th style="border: 1px solid black; text-align: left !important;">Item Ids</th>
                        @endif
                        <th class="text-center" style="border: 1px solid black; text-align: right !important;">Unit Price</th>
                        <th class="text-center" style="border: 1px solid black; text-align: center">Quantity</th>
                        <th class="text-right" width="8%" style="border: 1px solid black; text-align: right !important;">Sub Total</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($data->sale_details as $key => $details)
                        <tr>
                            <td style="border: 1px solid black; text-align: left !important;">{{ $key + 1 }}</td>
                            <td style="border: 1px solid black; text-align: left !important;">
                                {{ ucwords(optional($details->product)->product_name) }}
                                @if (count($details->serials) > 0)
                                <br>
                                 <p style="color: #2c65b4; font-size: 13px; margin: 0;">Serials No: {{ $details->serials->pluck('serial') }}</p> 
                             @endif
                            </td>
                            <td style="border: 1px solid black; text-align: left !important;">{{ ucfirst(optional(optional($details->product)->product_unit)->name) }}</td>
                            @if($has_additional_item_field)
                                <td style="border: 1px solid black; text-align: left !important;">{{ $details->product_item_ids }}</td>
                            @endif
                            <td style="border: 1px solid black; text-align: right !important;">{{ $details->unit_price }}</td>
                            <td style="border: 1px solid black; text-align: center">{{ $details->quantity }}</td>
                            <td style="border: 1px solid black; text-align: right !important;" class="text-right">{{ $details->product_sub_total }}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td colspan="{{ $col_span }}" style="border: none; padding: 0 !important; font-size: 14px !important; font-weight: bold">In Words: {{ ucwords(str_replace('-', ' ', inWords($data->total_sale_amount))) }} Tk.</td>
                        <td style="text-align:right; border: none; padding: 0 !important; font-size: 14px !important;">Sub Total : </td>
                        <td style="border: none; padding: 0 !important; font-size: 14px !important; padding-right: 5px; text-align: right !important;"> {{ number_format($data->total_sale_amount, 2) }}</td>
                    </tr>

                    <tr>
                        <td colspan="{{ $col_span }}" style="border: none; padding: 0 !important;"></td>
                        <td style="text-align:right; border: none; padding: 0 !important; font-size: 14px !important;">Tax : </td>
                        <td style="border: none; padding: 0 !important; font-size: 14px !important; padding-right: 5px; text-align: right !important;">{{ $data->invoice_tax }}</td>
                    </tr>
                    <tr style="border: none">
                        <td colspan="{{ $col_span }}" style="border: none; padding: 0 !important;"></td>
                        <td style="text-align:right; border: none; padding: 0 !important; font-size: 14px !important;">Discount : </td>
                        <td style="border: none; padding: 0 !important; font-size: 14px !important; padding-right: 5px; text-align: right !important;">{{ $data->invoice_discount }}</td>
                    </tr>
                    @if ($data->currier_amount != '')
                        <tr style="border: none;">
                            <td colspan="{{ $col_span }}" style="border: none; padding: 0 !important;"></td>
                            <td style="text-align:right; border: none; padding: 0 !important; font-size: 14px !important;">Courier Charge : </td>
                            <td style="border: none; padding: 0 !important; font-size: 14px !important; padding-right: 5px; text-align: right !important;">{{ number_format($data->currier_amount, 2) }}</td>
                        </tr>
                    @endif
                    <tr style="border: none;">
                        <td colspan="{{ $col_span }}" style="border: none; padding: 0 !important;"></td>
                        <td style="text-align:right; border: none; padding: 0 !important; font-size: 14px !important;">Invoice Total : </td>
                        <td style="border: none; padding: 0 !important; font-size: 14px !important; padding-right: 5px; text-align: right !important;">{{ number_format($invoice_total = $data->total_sale_amount + $data->invoice_tax + $data->currier_amount - $data->invoice_discount, 2) }}</td>
                    </tr>
                    <tr style="border: none;">
                        <td colspan="{{ $col_span }}" style="border: none; padding: 0 !important;"></td>
                        <td style="text-align:right; border: none; padding: 0 !important; font-size: 14px !important;">Previous Due : </td>
                        <td style="border: none; padding: 0 !important; font-size: 14px !important; padding-right: 5px; text-align: right !important;">{{ number_format($data->previous_due, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="{{ $col_span }}" style="border: none; padding: 0 !important;"></td>
                        <td style="text-align:right; border: none; padding: 0 !important; font-size: 14px !important;">Total Payable : </td>
                        <td style="border: none; padding: 0 !important; font-size: 14px !important; padding-right: 5px; text-align: right !important;">{{ number_format($invoice_total = $data->total_sale_amount + $data->invoice_tax + $data->currier_amount - $data->invoice_discount + $data->previous_due, 2) }}</td>
                    </tr>

                    <tr>
                        <td colspan="{{ $col_span }}" style="border: none; padding: 0 !important;"></td>
                        <td style="text-align:right; border: none; padding: 0 !important; font-size: 14px !important;">Given Amount : </td>
                        <td style="border: none; padding: 0 !important; padding-right: 5px; font-size: 14px !important; text-align: right !important;">{{ number_format($paid_amount = $data->paid + $data->change_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="{{ $col_span }}" style="border: none; padding: 0 !important;"></td>
                        <td style="text-align:right; border: none; padding: 0 !important; font-size: 14px !important;">Due Amount : </td>
                        <td style="border: none; padding: 0 !important; padding-right: 5px; font-size: 14px !important; text-align: right !important;">{{ number_format($invoice_total - $paid_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
