@php
$has_additional_item_field = optional($settings->where('title', 'Need Additional Item Id Field')->first())->options == 'need-additional-item-id-field';
$invoiceBarcode = optional($settings->where('title', 'Show Barcode in Invoice')->first())->options == 'yes';
$col_span = $has_additional_item_field ? 7 : 6;
if ($sale->sale_type == 'Package') {
    $col_span--;
}
// $sold_date   = new DateTime(fdate($sale->sale_date, 'Y-m-d'));
// $today_date  = new DateTime(date('Y-m-d'));
// $interval    = $sold_date->diff($today_date);
// $days_passed = $interval->format('%a');//now do whatever you like with $days
@endphp

@extends('admin.master')
@section('title', ' - Invoice Report')

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

            .html5-endscreen {
                top: 60px;
                display: none !important;
            }

        </style>
    @endpush

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Invoice</h1>
                <p>Sale Invoice</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="#">Invoice</a></li>
            </ul>
        </div>


        <div class="row mx-auto" style="width: 100%">
            <div class="col-md-12">

                <div class="tile" style="border: none !important;">
                    <section class="invoice tile-body" style="min-height: 740px !important;">
                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                @if (file_exists($sale->sale_company->company_logo))
                                    <img height="100" width="100" src="{{ asset($sale->sale_company->company_logo) }}"
                                        style="float: left">
                                @endif
                                @if ($sale->sale_company->name == 'Aziz Auto')
                                    <h2 style="font-size: 35px !important; color: #f6931b;" class="page-header">
                                        {{ $sale->sale_company->name }}</h2>
                                @else
                                    <h2 class="page-header">{{ $sale->sale_company->name }}</h2>
                                @endif
                                <h6>
                                    <span>Phone: {{ $sale->sale_company->phone }}</span><br>
                                    <span>Email: {{ $sale->sale_company->email }}</span><br>
                                    <span>{{ $sale->sale_company->address }}</span><br>
                                </h6>
                            </div>
                        </div>

                        <div class="row invoice-info">
                            <div class="col-6"><strong style="font-size: 18px">Bill To</strong>
                                <address style="font-size: 15px !important;">
                                    <strong>{{ $sale->sale_customer->name }}</strong><br>
                                    <strong>{{ $sale->sale_customer->customerId }}</strong><br>
                                    Address : {{ $sale->sale_customer->address }}<br>
                                    Phone : {{ $sale->sale_customer->phone }}<br>
                                    Email: {{ $sale->sale_customer->email }}
                                    @if ($sale->currier_id != '')
                                        <br>
                                        Courier: {{ $sale->currier->name }}
                                    @endif
                                </address>
                            </div>

                            <div class="col-6">
                                <h2 class="text-right" style="color: blue">INVOICE</h2>
                                <h4 class="text-right">
                                    <span class="text-secondary">Date :</span>
                                    {{ fdate($sale->sale_date, 'Y-m-d') }}
                                </h4>
                                <h4 class="text-right">
                                    <span class="text-secondary">Invoice ID:</span>
                                    {{ $sale->invoiceId }}
                                </h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-sm b" style="border: none">
                                    <thead>
                                        <tr style="font-size: 16px">
                                            <th style="border: 1px solid black">Sl.</th>
                                            @if ($has_additional_item_field)
                                                <th style="border: 1px solid black">Item Ids</th>
                                            @endif
                                            <th class="text-center" style="border: 1px solid black">Product Name</th>
                                            <th class="text-center" style="border: 1px solid black">Product Code</th>
                                            <th class="text-center" style="border: 1px solid black">Wty / Gty</th>
                                            @if ($sale->sale_type != 'Package')
                                                <th style="border: 1px solid black">Unit</th>
                                            @endif
                                            <th class="text-center" style="border: 1px solid black">Unit Price</th>
                                            <th class="text-center" style="width:10%;border: 1px solid black">Qty</th>
                                            <th class="text-right" width="8%" style="border: 1px solid black">Sub Total</th>
                                        </tr>
                                    </thead>

                                    <tbody style="border: none;">
                                        @if ($sale->sale_type == 'Package')
                                            @php $sl = 1; @endphp

                                            @foreach ($sale->sale_details->groupBy('package_id') as $key => $detail)
                                                @php $details = $detail->first(); @endphp
                                                <tr>
                                                    <td style="border: 1px solid black; font-size: 18px !important;">
                                                        {{ $sl++ }}</td>
                                                    <td style="border: 1px solid black; font-size: 14px !important;">
                                                        {{ ucwords(optional($details->package)->name) }}</td>
                                                    <td style="border: 1px solid black; font-size: 14px !important;">
                                                        {{ $details->package_price }}</td>
                                                    <td style="border: 1px solid black; font-size: 14px !important;">
                                                        {{ $details->package_qty }}</td>
                                                    <td style="border: 1px solid black; font-size: 14px !important;"
                                                        class="text-right">
                                                        {{ $details->package_qty * $details->package_price }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            @foreach ($sale->sale_details as $key => $details)
                                                {{-- @dd(optional($details->product)->warranty_days -$days_passed)
                                            @dd(optional($details->product)->guarantee_days - $days_passed) --}}
                                                <tr>
                                                    <td style="border: 1px solid black; font-size: 18px !important;">
                                                        {{ $key + 1 }}</td>
                                                    @if ($has_additional_item_field)
                                                        <td style="border: 1px solid black; font-size: 14px !important;">
                                                            {{ $details->product_item_ids }}</td>
                                                    @endif
                                                    <td style="border: 1px solid black; font-size: 14px !important;">
                                                        {{ ucwords(optional($details->product)->product_name) }}
                                                        <p style="margin: 0">
                                                            @foreach ($details->serials ?? [] as $item)
                                                                <span
                                                                    class="badge badge-primary pr-1">{{ $item->serial }}</span>
                                                            @endforeach
                                                        </p>
                                                        <p style="margin-bottom: 0">
                                                            {{-- @if ($details->product->warranty_days > 0) --}}
                                                            {{-- @php
                                                                    $warranty = \Carbon\Carbon::parse($sale->sale_date)->addDay($details->product->warranty_days);
                                                                    $now = \Carbon\Carbon::parse(now());

                                                                    $to = fdate($warranty, 'Ymd');
                                                                    $from = fdate($now, 'Ymd');

                                                                    $warranty_expire = $to - $from;
                                                                    $warranty_left = $warranty->diffInDays($now);
                                                                @endphp --}}
                                                            {{-- Warranty {{ $details->product->warranty_days }} days.
                                                            @endif --}}

                                                            {{-- @if ($details->product->guarantee_days > 0) --}}
                                                            {{-- @php
                                                                    $guarantee_days = \Carbon\Carbon::parse($sale->sale_date)->addDay($details->product->guarantee_days);
                                                                    $now = \Carbon\Carbon::parse(now());

                                                                    $to = fdate($guarantee_days, 'Ymd');
                                                                    $from = fdate($now, 'Ymd');

                                                                    $guarantee_expire = $to - $from;
                                                                    $guarantee_left = $guarantee_days->diffInDays($now);
                                                                @endphp --}}

                                                            {{-- Guarantee {{ $guarantee_expire > 0 ? $guarantee_left : 0 }} days. --}}
                                                            {{-- Guarantee {{ $details->product->guarantee_days }} days.
                                                            @endif --}}
                                                        </p>
                                                    </td>
                                                    <td style="border: 1px solid black; font-size: 14px !important;">
                                                        {{ ucwords(optional($details->product)->product_code) }}
                                                    </td>
                                                    <td style="border: 1px solid black; font-size: 14px !important;"
                                                        class="text-center">
                                                        {{-- @if ($details->product->warranty_days > 0)
                                                            @php
                                                                $warranty_days_left = $details->product->warranty_days - $days_passed;
                                                            @endphp
                                                            @if ($warranty_days_left > 0)
                                                                {{ $warranty_days_left }}
                                                            @else
                                                                0
                                                            @endif
                                                            @elseif($details->product->guarantee_days > 0)
                                                            @php
                                                                $guarantee_days_left = $details->product->guarantee_days - $days_passed;
                                                            @endphp
                                                            @if ($guarantee_days_left > 0)
                                                                {{ $guarantee_days_left }}
                                                            @else
                                                                0
                                                            @endif
                                                            @endif
                                                        days --}}
                                                        @if ($details->product->warranty_days > 0)
                                                            Wty {{ $details->product->warranty_days }} days.
                                                        @endif
                                                        @if ($details->product->guarantee_days > 0)
                                                            Gty {{ $details->product->guarantee_days }} days.
                                                        @endif
                                                    </td>
                                                    <td style="border: 1px solid black; font-size: 14px !important;">
                                                        {{ ucfirst(optional(optional($details->product)->product_unit)->name) }}
                                                    </td>
                                                    <td style="border: 1px solid black; font-size: 14px !important;">
                                                        {{ $details->unit_price }}</td>
                                                    <td style="border: 1px solid black; font-size: 14px !important;">
                                                        {{ $details->quantity . ' ' . optional(optional($details->product)->product_unit)->name }}
                                                    </td>
                                                    <td style="border: 1px solid black; font-size: 14px !important;"
                                                        class="text-right">{{ $details->product_sub_total }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td colspan="{{ $col_span }}"
                                                style="border: none; padding: 0 !important; font-size: 16px !important; font-weight: bold">
                                                In Words:
                                                {{ ucwords(str_replace('-', ' ', inWords($sale->total_sale_amount))) }}
                                            </td>
                                            <td style="text-align:right; border: none; padding: 0 !important; font-size: 16px !important;">
                                                Sub Total : </td>
                                            <td class="text-right"
                                                style="border: none; padding: 0 !important; font-size: 16px !important;">
                                                {{ number_format($sale->total_sale_amount, 2) }}</td>
                                     
                                        
                                        @if ($settings->where('title', 'Product Tax')->where('options', 'yes')->first())

                                            <tr>
                                                <td colspan="{{ $col_span }}"
                                                    style="border: none; padding: 0 !important;"></td>
                                                <td style="text-align:right; border: none; padding: 0 !important; font-size: 16px !important;">
                                                    Tax : </td>
                                                <td class="text-right"
                                                    style="border: none; padding: 0 !important; font-size: 16px !important;">
                                                    {{ $sale->invoice_tax }}</td>
                                            </tr>
                                        @endif
                                        <tr style="border: none">
                                            <td colspan="{{ $col_span }}" style="border: none; padding: 0 !important;">
                                            </td>
                                            <td
                                                style="text-align:right; border: none; padding: 0 !important; font-size: 16px !important;">
                                                Discount : </td>
                                            <td class="text-right"
                                                style="border: none; padding: 0 !important; font-size: 16px !important;">
                                                {{ $sale->invoice_discount }}</td>
                                        </tr>
                                        <tr style="border: none">
                                            <td colspan="{{ $col_span }}" style="border: none; padding: 0 !important;">
                                            </td>
                                            <td
                                                style="text-align:right; border: none; padding: 0 !important; font-size: 16px !important;">
                                                COD : </td>
                                            <td class="text-right"
                                                style="border: none; padding: 0 !important; font-size: 16px !important;">
                                                {{ $sale->cod_amount ?? 0 }}</td>
                                        </tr>
                                        @if ($sale->currier_amount != '')
                                            <tr style="border: none;">
                                                <td colspan="{{ $col_span }}"
                                                    style="border: none; padding: 0 !important;"></td>
                                                <td
                                                    style="text-align:right; border: none; padding: 0 !important; font-size: 16px !important;">
                                                    Courier Charge : </td>
                                                <td class="text-right"
                                                    style="border: none; padding: 0 !important; font-size: 16px !important;">
                                                    {{ number_format($sale->currier_amount, 2) }}</td>
                                            </tr>
                                        @endif
                                        <tr style="border: none;">
                                            <td colspan="{{ $col_span }}" style="border: none; padding: 0 !important;">
                                            </td>
                                            <td
                                                style="text-align:right; border: none; padding: 0 !important; font-size: 16px !important;">
                                                Invoice Total : </td>
                                            <td class="text-right"
                                                style="border: none; padding: 0 !important; font-size: 16px !important;">
                                                {{ number_format($invoice_total = $sale->total_sale_amount + $sale->invoice_tax + $sale->currier_amount + ($sale->cod_amount ?? 0) - $sale->invoice_discount, 2) }}
                                            </td>
                                        </tr>
                                        <tr style="border: none;">
                                            <td colspan="{{ $col_span }}" style="border: none; padding: 0 !important;">
                                            </td>
                                            <td
                                                style="text-align:right; border: none; padding: 0 !important; font-size: 16px !important;">
                                                Previous Due : </td>
                                            <td class="text-right"
                                                style="border: none; padding: 0 !important; font-size: 16px !important;">
                                                {{ number_format($sale->previous_due, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="{{ $col_span }}" style="border: none;">
                                                Received By:
                                                <span
                                                    style="border: none; padding: 0 !important; font-size: 16px !important; font-weight: bold">
                                                    {{ $sale->saleMeta->received->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td
                                                style="text-align:right; border: none; padding: 0 !important; font-size: 16px !important;">
                                                Total Payable : </td>
                                            <td class="text-right"
                                                style="border: none; padding: 0 !important; font-size: 16px !important;">
                                                {{ number_format($invoice_total = $sale->total_sale_amount + $sale->invoice_tax + $sale->currier_amount - $sale->invoice_discount + ($sale->cod_amount ?? 0) + $sale->previous_due, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="{{ $col_span }}" style="border: none;">
                                                Delivered By:
                                                <span
                                                    style="border: none; padding: 0 !important; font-size: 16px !important; font-weight: bold">
                                                    {{ $sale->saleMeta->delivered->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td
                                                style="text-align:right; border: none; padding: 0 !important; font-size: 16px !important;">
                                                Given Amount : </td>
                                            <td class="text-right"
                                                style="border: none; padding: 0 !important; font-size: 16px !important;">
                                                {{ number_format($paid_amount = $sale->paid + $sale->change_amount, 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="{{ $col_span }}" style="border: none; padding: 0 !important;">
                                                Note: <span style="width:40%">{{ $sale->note ?? 'N/A' }}<span>
                                            </td>
                                            <td
                                                style="text-align:right; border: none; padding: 0 !important; font-size: 16px !important;">
                                                Due Amount : </td>
                                            <td class="text-right"
                                                style="border: none; padding: 0 !important; font-size: 16px !important;">
                                                {{ number_format($sale->balance, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div class="row d-print-none mt-2">
                            <div class="col-12 text-right">
                                <div class="btn-group btn-corner">
                                    <form action="{{ route('email.send',$sale->id) }}" method="post" style="display: inline-block">
                                        @csrf
                                   <input type="hidden" name="type" value="sale">
                                   <button class="btn btn-sm btn-info" type="submit"><i class="fa fa-envelope"></i> Email</button>
                                    </form>

                                    <a class="btn btn-sm btn-success" href="" onclick="window.print()"><i
                                            class="fa fa-print"></i> Print</a>
                                    <a class="btn btn-sm btn-danger text-light" href="{{ route('sales.index') }}"><i
                                            class="fa fa-backward"></i> Back To List</a>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>




        <div class="d-print">
            <div class="row">
                <div class="col-sm-12">
                    @if ($invoiceBarcode)
                        <div class="col-sm-12 text-center">
                            {{-- @dd($sale->invoiceId) --}}
                            {{-- $sale->invoiceId --}}
                            {{-- <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($sale->invoiceId, "C128") }}" alt="barcode" style="width: 160px; height: 60px;" /> --}}
                        </div>
                    @endif

                    <h2 style="font-size: 14px; margin-top: 20px; width: 100%" class="text-center">
                        {{ config('app.thanks_message_in_sale_invoice') ?? 'Thanks For Coming' }}</h2>

                    <div style="width: 40% !important; float: left">
                        <b> Customer : {{ optional($sale->sale_customer)->name }} </b><br> <br><br> <br><br>
                        <hr>
                        <span style="margin-right: 50px; font-size: 16px;">Signature and Date</span>
                    </div>
                    <div style="width: 40% !important; float: right; margin-left: auto; text-align: right">
                        <b style="margin-right: 50px"> Issued By : {{ optional($sale->sale_company)->name }} </b><br>
                        <br><br> <br><br>
                        <hr>
                        <span style="margin-right: 50px; font-size: 16px;">Signature and Date</span>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
