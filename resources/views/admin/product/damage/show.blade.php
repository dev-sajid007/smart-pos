

@extends('admin.master')
@section('title', ' - Product Damage Details')

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
    </style>
@endpush


@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Damage Product Details</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body" style="height: 750px !important;">
                        <section class="invoice">
                            <div class="row mb-4">
                                <div class="col-12 text-center">
                                    <h2 class="page-header">{{ optional($productDamage->company)->name }}</h2>
                                    <h6>
                                        <span>Phone: {{ optional($productDamage->company)->phone }}</span><br>
                                        <span>Email: {{ optional($productDamage->company)->email }}</span><br>
                                        <span>Address: {{ optional($productDamage->company)->address }}</span><br>
                                    </h6>
                                    <h4>Damage Product Report</h4>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-sm table-bordered" style="border: none">
                                        <thead>
                                            <tr>
                                                <th style="border: 1px solid black">Sl.</th>
                                                <th style="border: 1px solid black">Product Name</th>
                                                <th style="border: 1px solid black">Quantity</th>
                                                <th style="border: 1px solid black; text-align: right">Unit Price</th>
                                                <th class="text-right" style="border: 1px solid black">Sub Total</th>
                                            </tr>
                                        </thead>

                                        @php $total_amount = 0; @endphp
                                        <tbody style="border: none">
                                            @foreach ($productDamage->items as $key => $details)
                                                <tr>
                                                    <td style="border: 1px solid black">{{ $key + 1 }}</td>
                                                    <td style="border: 1px solid black">{{ optional($details->product)->product_name }}</td>
                                                    <td style="border: 1px solid black">{{ $details->quantity . ' ' . optional(optional($details->product)->product_unit)->name }}</td>
                                                    <td style="border: 1px solid black; text-align: right">{{ $details->price }}</td>
                                                    <td style="border: 1px solid black" class="text-right">{{ $amount = $details->price * $details->quantity }}</td>
                                                </tr>
                                                @php $total_amount += $amount; @endphp
                                            @endforeach
                                        </tbody>

                                        <thead>
                                            <th style="border: 1px solid black" colspan="4"><strong>Total:</strong></th>
                                            <th style="border: 1px solid black" class="text-right">{{ number_format($total_amount, 2) }}</th>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="row d-print-none mt-2">
                                <div class="col-12 text-right">
                                    <a class="btn btn-primary" href="" onclick="window.print()">
                                        <i class="fa fa-print"></i> Print
                                    </a>
                                    <a class="btn btn-info" href="{{ route('sales.index') }}">
                                        <i class="fa fa-backward"></i> Back To List
                                    </a>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

            </div>
        </div>
        <div class="d-print">
            <div class="row">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6 text-right">
                    <b> Issued By : {{ optional($productDamage->company)->name }} </b><br> <br><br>
                    <hr>
                    Signature and Date
                </div>
            </div>
        </div>
    </main>
@endsection

@section('js')
    <script type="text/javascript">

        // window.print();

    </script>
@stop
