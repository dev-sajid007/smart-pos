
@extends('admin.master')
@section('title', ' - Product Package')

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">

                    <a href="{{ route('product-packages.index') }}" class="btn btn-success pull-right">
                        <i class="fa fa-list"></i> List
                    </a>

                    <h3><i class="fa fa-th-list"></i> Product Package Detail</h3>

                    <div class="tile-body" style="height: 740px !important;">


                        <div class="tile-body table-responsive">
                            <h5>Date: {{ fdate($productPackage->created_at, 'Y-m-d') }}</h5>
                            <h5>Description: {{ $productPackage->name }}</h5>
                            
                            <table class="table table-hover table-bordered table-sm" id="sampleTable">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Product Name</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-right">Price</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($productPackage->package_details as $key => $detail)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ optional($detail->product)->product_name }}</td>
                                            <td class="text-center">{{ $detail->quantity }}</td>
                                            <td class="text-right">{{ $detail->price }}</td>
                                            <td class="text-right">{{ $detail->amount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="4"><strong>Total:</strong></th>
                                        <th class="text-right">{{ number_format($productPackage->price, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- delete form -->
    @include('partials._delete_form')
@endsection

@section('footer-script')
    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
@endsection
