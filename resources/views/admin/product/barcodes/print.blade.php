@extends('admin.master')
@section('title', 'Barcode Print')
{{--@dd('sdfkjf')--}}
@section('content')
    <style>
        @media print {
            .page-break {
                display: block;
                page-break-after: always;
                page-break-before: always;
            }
            .d-print-block {
                display: block;
            }
        }
        .d-print-block {
            display: none;
        }
    </style>

    <main class="app-content">
        <!-- Breadcrumb -->
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-text-o"></i> Barcode Print</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="#"> Barcode Print</a></li>
            </ul>
        </div>

        <div class="row multiple-print">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body" style="min-height: 640px !important;">
                        <section class="invoice">
                            <div class="row d-print-none mt-2">

                                <div class="col-12 float-right">
                                    <div class="btn-group float-left ml-5">
                                        <strong style="margin-right: 10px; margin-top: 3px">Print Size: </strong>

                                        <div class="form-check-inline mt-1" title="Mini">
                                            <label class="form-check-label" style="cursor: pointer">
                                                <input type="radio" class="form-check-input" value="mini-size" name="optradio" style="cursor: pointer">0.8 inch X 1.3 inch
                                            </label>
                                        </div>
                                        <div class="form-check-inline mt-1" title="Small">
                                            <label class="form-check-label" style="cursor: pointer">
                                                <input type="radio" class="form-check-input" checked value="small-size" name="optradio" style="cursor: pointer">1.1 inch X 3.5 inch
                                            </label>
                                        </div>
                                        <div class="form-check-inline mt-1" title="Medium">
                                            <label class="form-check-label" style="cursor: pointer">
                                                <input type="radio" class="form-check-input" name="optradio" value="medium-size" style="cursor: pointer">2.4 inch X 6.45 inch
                                            </label>
                                        </div>
                                    </div>
                                    <div class="btn-group float-right">

                                        @if(request('type') == 'normal')
                                            <button class="btn btn-primary" type="button" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
                                        @endif
                                        <button class="btn btn-success" type="button" onclick="singlePrint()"><i class="fa fa-print"></i>Label Print</button>
                                        <a class="btn btn-secondary" href="{{ route('barcodes.index') }}"><i class="fa fa-backward"></i> Back To List</a>
                                    </div>
                                </div>
                            </div>

                            @if(request('type') == 'normal')
                                <br>
                                <br>

                                <div class="row ">
                                    <div class="col-md-12">
                                        <div class="row">
                                            @for ($i = 1; $i <= 40; $i++)
                                                <div class="col-md-3 text-center mb-5">
                                                    <div class="text-center">
                                                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" alt="barcode" style="width: 160px; height: 60px;" />
                                                    </div>
                                                    <b>{{ $barcode->barcode_number }} &nbsp; &nbsp; Tk. {{ number_format($barcode->product->product_price, 2) }}</b>
                                                    <br>
                                                    <b> {{ $barcode->product->product_name }}</b>
                                                </div>

                                                @if ($i == 40)
                                                    <div class="page-break"></div>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            @else
                                <br>
                                <br>

                                {{--                            <div class="row">--}}
                                {{--                                <div class="col-md-3 text-center mb-5 d-print-none">--}}
                                {{--                                    <div class="text-center">--}}
                                {{--                                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" alt="barcode" style="width: 160px; height: 60px;" />--}}
                                {{--                                    </div>--}}
                                {{--                                    <b>{{ $barcode->barcode_number }}</b>--}}
                                {{--                                    <br>--}}
                                {{--                                    <b> {{ $barcode->product->product_name }}</b>--}}
                                {{--                                </div>--}}
                                {{--                            </div>--}}

                                <div class="row" id="DivIdToPrint">
                                    <div class="col-md-12">

                                        <!-- // per page one item mini new -->
                                        <div class="row mini-size per-page-1">
{{--                                            <table>--}}
{{--                                                <tr style="width: 8.5cm !important; background: red">--}}
{{--                                                    <td>--}}
{{--                                                        <div style="width: 3.5cm !important; height: 1.8cm !important; background: #0b70de">--}}
{{--                                                            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" alt="barcode" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" style="width: 3.8cm !important; height: 1.8cm !important;" />--}}
{{--                                                            --}}{{--                                                    <div style="width: 3.8cm !important;">--}}
{{--                                                            --}}{{--                                                        <p style="text-align: center !important; font-size: 7px;">{{ $barcode->barcode_number }}>Tk. {{ number_format($barcode->product->product_price, 2) }}</span></p>--}}
{{--                                                            --}}{{--                                                        <p style="text-align: center !important; font-size: 6px;"> {{ $barcode->product->product_name }}</p>--}}
{{--                                                            --}}{{--                                                    </div>--}}
{{--                                                        </div>--}}
{{--                                                    </td>--}}
{{--                                                    <td><div style="width: 3.5cm !important; height: 1.8cm !important; background: #0b70de">--}}
{{--                                                            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" alt="barcode" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" style="width: 3.8cm !important; height: 1.8cm !important;" />--}}
{{--                                                            --}}{{--                                                    <div style="width: 3.8cm !important;">--}}
{{--                                                            --}}{{--                                                        <p style="text-align: center !important; font-size: 7px;">{{ $barcode->barcode_number }}>Tk. {{ number_format($barcode->product->product_price, 2) }}</span></p>--}}
{{--                                                            --}}{{--                                                        <p style="text-align: center !important; font-size: 6px;"> {{ $barcode->product->product_name }}</p>--}}
{{--                                                            --}}{{--                                                    </div>--}}
{{--                                                        </div>--}}
{{--                                                    </td>--}}
{{--                                                </tr>--}}
{{--                                            </table>--}}
                                            <div style="width: 8.5cm !important; height: 2.8cm !important; padding: 0.2cm !important; padding-top: 0.1cm !important; float: left; !important;">
                                                <div style="width: 3.8cm !important; height: 1.8cm !important; float: left !important;">
                                                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" alt="barcode" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" style="width: 3.8cm !important; height: 1.6cm !important;" />
                                                    <div style="width: 3.8cm !important;">
                                                        <p style="text-align: center !important; font-size: 8px; ">{{ $barcode->barcode_number }}Tk. {{ number_format($barcode->product->product_price, 2) }}</span></p>
                                                        <p style="text-align: center !important; font-size: 8px; padding-top:-0.6cm !important"> {{ $barcode->product->product_name }}</p>
                                                    </div>
                                                </div>

                                                <div style="width: 0.4cm !important; float: left">&nbsp;</div>

                                                <div style="width: 3.8cm !important; height: 1.8cm !important; float: left !important;">
                                                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" alt="barcode" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" style="width: 3.8cm !important; height: 1.6cm !important;" />
                                                    <div style="width: 3.8cm !important;">
                                                        <p style="text-align: center !important; font-size: 8px;">{{ $barcode->barcode_number }}Tk. {{ number_format($barcode->product->product_price, 2) }}</span></p>
                                                        <p style="text-align: center !important; font-size: 8px; padding-top:-0.6cm !important"> {{ $barcode->product->product_name }}</p>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- // per page one item  -->
                                        <div class="row small-size per-page-1">
                                            <div style="width: 3.5in !important; height: 1.1in !important; padding-left: 0.2in !important; padding-top: .04in">
                                                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" alt="barcode" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" style="width: 2in !important; height: .4in !important;" />
                                                <div style="width: 2in !important; ">
                                                    <p style="text-align: center !important; font-size: 9px; margin-top: 1.5px">{{ $barcode->barcode_number }} <span style="margin-left: 10px">Tk. {{ number_format($barcode->product->product_price, 2) }}</span></p>
                                                    <p style="text-align: center !important; font-size: 7px; margin-top: -10px !important;"> {{ $barcode->product->product_name }}</p>
                                                </div>

                                            </div>
                                        </div>

                                        <br>

                                        <!-- // per page one item  -->
                                        <div class="row medium-size per-page-1">
                                            <div style="width: 6.45in !important; height: 2.4in !important; padding-left: 1.2in !important; padding-top: .14in">
                                                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" alt="barcode" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" style="width: 4in !important; height: 1.5in !important;" />
                                                <div style="width: 4in !important; ">
                                                    <p style="text-align: center !important; font-size: 18px; margin-top: 2px">{{ $barcode->barcode_number }} &nbsp; <span>Tk. {{ number_format($barcode->product->product_price, 2) }}</span></p>
                                                    <p style="text-align: center !important; font-size: 18px; margin-top: -20px !important;"> {{ $barcode->product->product_name }}d</p>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="row medium-sizedd per-page-1" style="display: none">
                                            <div style="width: 220px !important; margin-top: -18px !important;">
                                                <table>
                                                    <tr>
                                                        <td style="margin-left: 3px !important; margin-right: 5px !important">
                                                            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" alt="barcode" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" style="width: 200px !important; height: 30px !important;" />
                                                            <p style="text-align: center; font-size: 9px; margin-top: 2px">{{ $barcode->barcode_number }} <span style="margin-left: 10px">Tk.: {{ number_format($barcode->product->product_price, 2) }}</p>
                                                            <p style="text-align: center; font-size: 7px; margin-top: -7px !important;"> {{ $barcode->product->product_name }}</p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                        {{--                                    <div class="row da-print-block per-page-4">--}}
                                        {{--                                        <div style="width: 220px !important; margin-top: -5px !important;">--}}
                                        {{--                                            <table>--}}
                                        {{--                                                <tr style="margin-top: -5px !important">--}}
                                        {{--                                                    <td style="margin-left: 3px !important; margin-right: 5px !important">--}}
                                        {{--                                                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" alt="barcode" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" style="width: 100px !important; height: 13px !important;" />--}}
                                        {{--                                                        <p style="text-align: center; font-size: 5px; margin-top: 2px">{{ $barcode->barcode_number }}</p>--}}
                                        {{--                                                        <p style="text-align: center; font-size: 5px; margin-top: -5px !important;"> {{ $barcode->product->product_name }}</p>--}}
                                        {{--                                                    </td>--}}
                                        {{--                                                    <td>&nbsp;</td>--}}
                                        {{--                                                    <td style="margin-left: 3px">--}}
                                        {{--                                                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" alt="barcode" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" style="width: 100px !important; height: 13px !important;" />--}}
                                        {{--                                                        <p style="text-align: center; font-size: 5px; margin-top: 2px">{{ $barcode->barcode_number }}</p>--}}
                                        {{--                                                        <p style="text-align: center; font-size: 5px; margin-top: -5px !important;"> {{ $barcode->product->product_name }}</p>--}}
                                        {{--                                                    </td>--}}
                                        {{--                                                </tr>--}}
                                        {{--                                                    <td style="margin-left: 3px">--}}
                                        {{--                                                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" alt="barcode" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" style="width: 100px !important; height: 13px !important;" />--}}
                                        {{--                                                        <p style="text-align: center; font-size: 5px; margin-top: 2px">{{ $barcode->barcode_number }}</p>--}}
                                        {{--                                                        <p style="text-align: center; font-size: 5px; margin-top: -5px !important;"> {{ $barcode->product->product_name }}</p>--}}
                                        {{--                                                    </td>--}}
                                        {{--                                                    <td></td>--}}
                                        {{--                                                    <td style="margin-left: 3px">--}}
                                        {{--                                                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" alt="barcode" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" style="width: 100px !important; height: 13px !important;" />--}}
                                        {{--                                                        <p style="text-align: center; font-size: 5px; margin-top: 2px">{{ $barcode->barcode_number }}</p>--}}
                                        {{--                                                        <p style="text-align: center; font-size: 5px; margin-top: -5px !important;"> {{ $barcode->product->product_name }}</p>--}}
                                        {{--                                                    </td>--}}
                                        {{--                                                </tr>--}}
                                        {{--                                            </table>--}}
                                        {{--                                        </div>--}}
                                    </div>
                                </div>
                            @endif
                        </section>
                    </div>
                </div>
            </div>
        </div>

        @if(request('type') == 'normal')
            <div class="row d-none" id="DivIdToPrint">
                <div class="col-md-12">
                    <div class="tile">
                        <div class="tile-body">
                            <section class="invoice">
                                <div class="row ">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3 text-center mb-5 mx-auto" style="text-align: center !important;">
                                                <div class="text-center">
                                                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, "C128") }}" alt="barcode" style="width: 160px; height: 60px;" />
                                                </div>
                                                <b>{{ $barcode->barcode_number }}</b>
                                                <br>
                                                <b> {{ $barcode->product->product_name }}</b>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </main>
@endsection

@section('js')
    <script type="text/javascript">
        // window.print();

        $(document).ready(function () {
            $('.small-size').show()
            $('.mini-size').hide()
            $('.medium-size').hide()
        })

        $('.form-check-input').click(function () {
            $('.small-size').hide()
            $('.mini-size').hide()
            $('.medium-size').hide()
            let size = $('input[name="optradio"]:checked').val();
            $('.' + size).show()
        })


        function singlePrint() {
            // $('.single-print').print()
            var divToPrint=document.getElementById('DivIdToPrint');
            $('.small-size').hide()
            $('.mini-size').hide()
            $('.medium-size').hide()

            let size = $('input[name="optradio"]:checked').val();
            $('.' + size).show()


            var newWin=window.open('','Print-Window');

            newWin.document.open();

            newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

            newWin.document.close();

            setTimeout(function(){newWin.close();},10);
        }
    </script>
@stop
