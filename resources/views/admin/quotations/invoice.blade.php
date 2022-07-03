@extends('admin.master')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Invoice</h1>
                <p>Quotation Invoice</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="#">Invoice</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <section class="invoice">
                        <div class="row mb-4">
                            <div class="col-6">
                                <h2 class="page-header"><i
                                            class="fa fa-globe"></i> {{ $quotation->quotation_company->name }} </h2>
                            </div>
                            <div class="col-6">
                                <h5 class="text-right">Date: {{ $quotation->quotation_date }}</h5><br>
                            </div>
                        </div>
                        <div class="row invoice-info">
                            <div class="col-6">
                                From
                                <address><strong>{{ $quotation->quotation_company->name }}</strong><br>
                                    Address : {{ $quotation->quotation_company->address }}<br>
                                    Phone : {{ $quotation->quotation_company->phone }}<br>
                                    Email: {{ $quotation->quotation_company->email }}
                                </address>
                            </div>
                            <div class="col-6">To
                                <address><strong>{{ $quotation->quotation_customer->name }}</strong><br>
                                    Address : {{ $quotation->quotation_customer->address }}<br>
                                    Phone : {{ $quotation->quotation_customer->phone }}<br>
                                    Email: {{ $quotation->quotation_customer->email }}
                                </address>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Quotation Reference : {{ $quotation->quotation_reference }}</th>
                                        <th colspan="3"></th>
                                    </tr>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Unit Price</th>
                                        <th>Quantity</th>
                                        <th>Sub Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($quotation->quotation_details as $details)
                                        <tr>
                                            <td>{{ $details->product->product_name }}</td>
                                            <td>{{ $details->unit_price }}</td>
                                            <td>{{ $details->quantity.' '.$details->product->product_unit->name }}</td>
                                            <td>{{ $details->product_sub_total }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3" style="text-align:right">Total :</td>
                                        <td>{{ $quotation->sub_total }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align:right">Discount :</td>
                                        <td>{{ $quotation->invoice_discount }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align:right">Tax :</td>
                                        <td>{{ $quotation->invoice_tax }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align:right">Total Payable :</td>
                                        <td>{{ $quotation->total_payable }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><b> Customer : {{ $quotation->quotation_customer->name }} </b><br> <br><br>
                                            <hr>
                                            Signature and Date
                                        </td>
                                        <td>&nbsp;</td>
                                        <td style="text-align:right"><b>Issued By
                                                : {{ $quotation->quotation_company->name }} </b> <br> <br> <br>
                                            <hr>
                                            Signature and Date
                                        </td>
                                    </tr>
                                    <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row d-print-none mt-2">
                            <div class="col-12 text-right"><a class="btn btn-primary" onclick="window.print();"
                                                              target="_blank"><i class="fa fa-print"></i> Print</a>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('footer-script')
    <script>
        $(() => {
            @if(session('success'))
                setTimeout(() => {
                    window.print()

                }, 3000);
                @else
                window.print()
            @endif

        });

    </script>
@endsection