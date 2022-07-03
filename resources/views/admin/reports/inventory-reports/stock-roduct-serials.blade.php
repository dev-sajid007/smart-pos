
@extends('admin.master')
@section('title', ' - Product Wise Stock Report')

@push('style')
    <style>
        @media print {
            tr {
                page-break-inside: avoid;
            }
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
                        <div class="row">
                            <div class="col-md-12 mx-auto">

                                <div class="table-responsive px-3">
                                    <h3>Product Name: {{ $product->product_name }} <span class="badge badge-success">{{ $product->product_serials->count() }} {{ optional($product->product_unit)->name }}</span></h3>
                                    <h4>Product Code: {{ $product->product_code }}</h4>
                                    <h4>Product Categpry: {{ optional($product->category)->name }}</h4>

                                    <br>
                                    <h5>Product Serials:</h5>
                                    <hr>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 pl-4">
                                <table class="table table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sl.</th>
                                            <th>Souce</th>
                                            <th>Serial</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($product->product_serials as $key => $serial)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $serial->source }}</td>
                                                <td>{{ $serial->serial }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                    <span class="badge badge-primary p-2" style="background: #52696f !important; font-size: 13px"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
