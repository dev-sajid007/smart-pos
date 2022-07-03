@extends('admin.master')

@section('title', ' - Purchase')

@push('style')
@endpush

@section('content')



    <!-- filter section -->
    @include('admin.purchase.purchases.includes._add_product_serial_modal')

    <main class="app-content">
        <form class="form-horizontal" id="purchaseForm" method="POST" action="{{ route('purchases.store') }}">
            @csrf
            <div class="row">
                <div class="col-sm-9">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <i class="fa fa-plus"></i> Add New Purchases
                            <a href="{{ route('purchases.index') }}" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-eye"></i> Purchase List
                            </a>
                        </div>

                        <div class="card-body">


                            @include('partials._alert_message')


                            <!-- filter section -->
                            @include('admin.purchase.purchases.includes.filter-section')




                            <!-- product entry section -->
                            @include('admin.purchase.purchases.includes.product-entry')




                            <!-- product display section -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="3%">Sl.</th>
                                                <th>Product Name</th>
                                                <th>Product Code</th>
                                                {{-- <th>Expire Date</th> --}}
                                                <th width="100px">Product Qty</th>
                                                <th width="100px">Unit Cost</th>
                                                <th width="80px">Free Qty</th>
                                                <th width="100px">Subtotal</th>
                                                <th width="5%"></th>
                                            </tr>
                                        </thead>

                                        <tbody class="product-display">

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <!-- Sidebar Total calculation -->
                @include('admin.purchase.purchases.includes.sidebar-total')
            </div>
        </form>
    </main>

    {{-- @include('admin.includes.date_field') --}}
@endsection

@section('footer-script')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('jq/loadDetails.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/custom_js/toggle-full-screen.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/custom_js/individual-purchase-create.js') }}?{{ fdate(now(), 'Y-m-d H:i') }}"></script>
    <script  src="{{ asset('assets/admin/js/plugins/bootstrap-datepicker.min.js')}}"></script>

    <script>
        $('.dateField').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        });
    </script>
@endsection
