
@php
    $currierService                 = optional($settings->where('title', 'Courier Service For Sale')->first())->options == 'yes';
    $productTax                     = optional($settings->where('title', 'Product Tax')->first())->options == 'yes';
    $customerPoint                  = optional($settings->where('title', 'Customer Point')->first())->options == 'yes';
    $hasProductRak                  = optional($settings->where('title', 'Product Rak In Product')->first())->options == 'yes';
    $productDiscount                = optional($settings->where('title', 'Product Discount')->first())->options == 'yes';
    $is_default_pos_print           = optional($settings->where('title', 'Sale Default Printing')->first())->options == 'pos-print';
    $has_product_wise_discount      = optional($settings->where('title', 'Product Discount')->first())->options == 'yes';
    $has_additional_item_field      = optional($settings->where('title', 'Need Additional Item Id Field')->first())->options == 'need-additional-item-id-field';
    $allowSalesAvailableQuantity    = optional($settings->where('title', 'Allow Sales When Stock Not Available')->first())->options == 'yes';
@endphp

@extends('admin.master')

@section('title', ' - Edit Sale')

@push('style')
    <style>
        .form-group {
            margin-bottom: 8px;
        }

        .is-invalid{
            border-color: #f78484cc !important;
        }
        .is-valid{
            border-color: #38c18287 !important;
        }

        .profit-column {
            margin-top: -22px !important;
            padding-right: 5px !important;
            text-align: right !important;
            width: 100% !important;
            font-size: 11px !important;"
        }


        .input-group-text {
            width: 110px !important;
            padding-left: 5px;
        }

        .input-group-prepend, .height-30 {
            height: 30px !important;
        }

    </style>
@endpush

@section('content')
    @include('admin.people.customers.quickadd', ['errors' => $errors])

    <main class="app-content">
        <form class="form-horizontal" id="saleForm" name="saleForm" method="POST" action="{{ route('sales.update', $sale->id) }}">
            @csrf @method('PUT')

            <div class="row form-screen">
                <div class="col-md-9">
                    @include('partials._alert_message')

                    <div class="bs-component">
                        <div class="card">
                            <h4 class="card-header bg-primary text-white">
                                <span class="float-left"><i class="fa fa-edit"></i> Edit Sale</span>
                                <span class="float-right">
                                    <a href="#" onclick="toggleFullScreen()" title="Full Screen" style="color:#fff;"><i class="fa fa-expand"></i></a>
                                </span>
                            </h4>

                            <div class="card-body">

                                <input type="hidden" class="old-customer-id" value="{{ $sale->fk_customer_id }}">
                                <input type="hidden" class="sale-type" name="sale_type" value="{{ $sale->sale_type }}">

                                <div class="row">
                                    <div class="col-sm-12">

                                        <!-- Customer and Date Section -->
                                        @include('admin.sales.edit-sale.edit-filter-heading-table')
                                    </div>
                                    <br>
                                </div>
                                <div class='row'>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            @include('admin.sales.edit-sale.item-table')
                                        </div>
                                    </div>
                                    <div class='col-xs-12 col-sm-12'>
                                        <div class="float-right">
                                            <button class="btn btn-primary float-right btn-sm addmore" onclick="row_increment()" tabindex="-1" id="addMore" type="button">+ Add More </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('admin.sales.edit-sale.total-calculation-sidebar')
            </div>
        </form>
    </main>

    <input type="hidden" class="customer_due_limit" value="{{ $sale->customer->due_limit ?? 999999999999999 }}">
    <input type="hidden" class="check-has-additional-item-field" value="{{ $has_additional_item_field ? 'yes' : 'no' }}">
    <input type="hidden" class="check-allow-sale-available-quantity" value="{{ $allowSalesAvailableQuantity ? 'yes' : 'no' }}">
    <input type="hidden" class="has-product-rak" value="{{ $hasProductRak ? 'yes' : 'no' }}">
    <input type="hidden" class="has-product-discount" value="{{ $has_product_wise_discount ? 'yes' : 'no' }}">
    <input type="hidden" class="currier-service-for-sale" value="{{ $currierService ? 'yes' : 'no' }}">
    <!-- customer point -->
    <input type="hiddens" class="customer-point-type">
    <input type="hiddens" class="customer-amount-of">
    <input type="hiddens" class="customer-amount">
@endsection

@section('js')
    <script src="{{ asset('assets/admin/js/plugins/select2.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('assets/admin/jq/customerQuickAdd.js') }}"></script>
    <script src="{{ asset('jq/loadDetails.js') }}"></script>>
    <script type="text/javascript" src="{{ asset('assets/custom_js/sale-edit.js') }}?{{ fdate(now(), 'Y-m-d H') }}"></script>
    <script>
        function toggleFullScreen() {
            {
                if ((document.fullScreenElement && document.fullScreenElement !== null) ||
                    (!document.mozFullScreen && !document.webkitIsFullScreen)) {
                    if (document.documentElement.requestFullScreen) {
                        document.documentElement.requestFullScreen();
                    } else if (document.documentElement.mozRequestFullScreen) {
                        document.documentElement.mozRequestFullScreen();
                    } else if (document.documentElement.webkitRequestFullScreen) {
                        document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
                    }
                    $('body').addClass('sidenav-toggled')
                } else {
                    if (document.cancelFullScreen) {
                        document.cancelFullScreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.webkitCancelFullScreen) {
                        document.webkitCancelFullScreen();
                    }
                    $('body').removeClass('sidenav-toggled')
                }
            }
        }
    </script>
@stop
