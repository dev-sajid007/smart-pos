
@php
    $has_additional_item_field = optional($settings->where('title', 'Need Additional Item Id Field')->first())->options == 'need-additional-item-id-field';
    $is_default_pos_print = optional($settings->where('title', 'Sale Default Printing')->first())->options == 'pos-print';
    $allowSalesAvailableQuantity = optional($settings->where('title', 'Allow Sales When Stock Not Available')->first())->options == 'yes';
    $productTax = optional($settings->where('title', 'Product Tax')->first())->options == 'yes';
    $productDiscount = optional($settings->where('title', 'Product Discount')->first())->options == 'yes';
    $hasProductRak = optional($settings->where('title', 'Product Rak In Product')->first())->options == 'yes';
    $has_product_wise_discount = optional($settings->where('title', 'Product Discount')->first())->options == 'yes';
@endphp

@extends('admin.master')

@section('title', ' - Sale')

@push('style')
    <style>
        .form-group {
            margin-bottom: 8px;
        }

        .loading {
            display: none;
            justify-content: center;
            align-items: center;
            width: 100%;
            position: absolute;
            height: 100%;
            z-index: 11111;
            background: #ffffffc7;
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
        <form class="form-horizontal" id="saleForm" name="saleForm" method="POST" action="{{ route('sales.store') }}">
            @csrf
            <div class="row form-screen">
                <div class="col-md-9">
                    @include('partials._alert_message')

                    <div class="bs-component">
                        <div class="card">
                            <h4 class="card-header bg-primary text-white">
                                <span class="float-left"><i class="fa fa-plus"></i> Add New Sale <span style="color: #8ccfde !important; font-size: 12px !important;"> (Holesale)</span></span>
                                <span class="float-right">
                                    <a href="#" onclick="toggleFullScreen()" title="Full Screen" style="color:#fff;
                                    "><i class="fa fa-expand"></i></a>
                                </span>
                            </h4>

                            <input type="hidden" name="sale_type" value="holesale">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        @include('admin.sales.includes.filter-heading-table')
                                    </div>
                                    <br>
                                </div>

                                <div class='row'>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered" id="table">
                                                <!-- table header -->
                                                <thead>
                                                <tr>
                                                    <th width="3%">Sl.</th>
                                                    <th>Product Name</th>
                                                    <th style="display: none">Product ID</th>
                                                    @if($has_additional_item_field)
                                                        <th style="width: 10%;">Item Id</th>
                                                    @endif
                                                    <th style="width: 10%;" class="text-center">Quantity</th>
                                                    <th width="12%">Price</th>
                                                    @if($has_product_wise_discount)
                                                        <th style="width: 10%;">Discount</th>
                                                    @endif
                                                    <th width="10%" class="text-center">Stock</th>
                                                    <th width="12%">Subtotal</th>
                                                    <th width="1%"></th>
                                                </tr>
                                                </thead>

                                                <tbody class="item-table-body">
                                                @if (old('product_names') == true)
                                                    @foreach(old('product_names') as $key => $value)

                                                        <tr>
                                                            <td><span class="item-serial-counter">{{ $key + 1 }}</span></td>
                                                            <td>
                                                                <input type="text" name="product_names[]" class="form-control product-name" autocomplete="off" value="{{ old('product_names')[$key] }}">
                                                                <input type="hidden" class="product-cost" name="product_costs[]" value="{{ old('product_names')[$key] }}">
                                                                <input type="hidden" class="product-id" name="product_ids[]" value="{{ old('product_ids')[$key] }}">
                                                                <input type="hidden" class="profit-column-input" name="profit_columns[]" value="{{ old('profit_columns')[$key] }}">

                                                                <p class="profit-column" >{{ old('profit_columns')[$key] }}</p>

                                                                <p class="product-rak-text" style="color: #156984; margin-top: -14px; margin-bottom: 0; font-size: 11px">{{ old('profit_columns')[$key] }}</p>
                                                                <input class="product-rak-input" type="hidden" name="product_rak_names[]" value="{{ old('profit_columns')[$key] }}">

                                                                <input type="hidden" name="product_taxes[]" class="tax-percent" value="{{ old('product_taxes')[$key] }}">
                                                            </td>
                                                            @if($has_additional_item_field)
                                                                <td><input type="text" name="product_item_ids[]" value="{{ old('product_item_ids')[$key] }}" class="form-control" placeholder="123"></td>
                                                            @endif
                                                            <td>
                                                                <input type="text" name="quantities[]" value="{{ old('quantities')[$key] }}" class="form-control text-center item-quantity changesNo" autocomplete="off" onkeyup="itemQuantityKeyUp(this)" onkeypress="return IsNumeric(event);">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="unit_prices[]" value="{{ old('unit_prices')[$key] }}" class="form-control unit-price changesNo" autocomplete="off" onkeyup="priceEnter(event, this)" onkeypress="return IsNumeric(event);">
                                                            </td>

                                                            @if($has_product_wise_discount)
                                                                <td>
                                                                    <input type="text" name="product_discounts[]" value="{{ old('product_discounts')[$key] }}" class="form-control product-discount changesNo" autocomplete="off" onkeyup="priceEnter(event, this)" onkeypress="return IsNumeric(event);">
                                                                </td>
                                                            @else
                                                                <td style="display: none">
                                                                    <input type="text" name="product_discounts[]" class="product-discount">
                                                                </td>
                                                            @endif
                                                            <td>
                                                                <input type="text" name="stock_available[]" value="{{ old('stock_available')[$key] }}" readonly class="form-control text-center text-danger available-stock">
                                                            </td>

                                                            <td><input type="text" readonly name="product_sub_total[]"value="{{ old('product_sub_total')[$key] }}" class="form-control subtotal"></td>

                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-sm" title="Delete This Row" onclick="removeRow(this)"><i class="fa fa-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td><span class="item-serial-counter">1</span></td>
                                                        <td>
                                                            <input type="text" name="product_names[]" class="form-control product-name" autocomplete="off">
                                                            <input type="hidden" class="product-cost" name="product_costs[]">
                                                            <input type="hidden" class="product-id" name="product_ids[]">
                                                            <input type="hidden" class="profit-column-input" name="profit_columns[]">

                                                            <p class="profit-column" ></p>

                                                            <p class="product-rak-text" style="color: #156984; margin-top: -14px; margin-bottom: 0; font-size: 11px"></p>
                                                            <input class="product-rak-input" type="hidden" name="product_rak_names[]">

                                                            <input type="hidden" name="product_taxes[]" class="tax-percent">
                                                        </td>

                                                        @if($has_additional_item_field)
                                                            <td><input type="text" name="product_item_ids[]" value="" class="form-control" placeholder="123"></td>
                                                        @endif
                                                        <td>
                                                            <input type="text" name="quantities[]" class="form-control text-center item-quantity changesNo" autocomplete="off" onkeyup="itemQuantityKeyUp(this)" onkeypress="return IsNumeric(event);">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="unit_prices[]" class="form-control unit-price changesNo" autocomplete="off" onkeyup="priceEnter(event, this)" onkeypress="return IsNumeric(event);">
                                                        </td>

                                                        @if($has_product_wise_discount)
                                                            <td>
                                                                <input type="text" name="product_discounts[]" class="form-control product-discount changesNo" autocomplete="off" onkeyup="priceEnter(event, this)" onkeypress="return IsNumeric(event);">
                                                            </td>
                                                        @else
                                                            <td style="display: none">
                                                                <input type="text" name="product_discounts[]" class="product-discount">
                                                            </td>
                                                        @endif
                                                        <td>
                                                            <input type="text" name="stock_available[]" readonly class="form-control text-center text-danger available-stock">
                                                        </td>

                                                        <td><input type="text" readonly name="product_sub_total[]" class="form-control subtotal"></td>

                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm" title="Delete This Row" onclick="removeRow(this)"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
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

                @include('admin.sales.includes.right-side-total-calculations')
            </div>
        </form>
    </main>

    <input type="hidden" class="customer_due_limit" value="999999999999999">
    <input type="hidden" class="check-has-additional-item-field" value="{{ $has_additional_item_field ? 'yes' : 'no' }}">
    <input type="hidden" class="check-allow-sale-available-quantity" value="{{ $allowSalesAvailableQuantity ? 'yes' : 'no' }}">
    <input type="hidden" class="has-product-rak" value="{{ $hasProductRak ? 'yes' : 'no' }}">
    <input type="hidden" class="has-product-discount" value="{{ $has_product_wise_discount ? 'yes' : 'no' }}">
@endsection

@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{ asset('assets/admin/jq/customerQuickAdd.js') }}"></script>
    <script src="{{ asset('jq/loadDetails.js') }}"></script>


    <script type="text/javascript" src="{{ asset('assets/custom_js/toggle-full-screen.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/custom_js/holesale-create.js') }}"></script>

    <script>
        loadDetails({
            selector: '.receiver',
            url: '/sale-distributors?type=receiver',
            select: (event, ui) => {
                $('input[name=received_by]').val(ui.item.data.id).removeClass('is-invalid')
                $('.receiver').removeClass('is-invalid')
            },
            search: (event) => {
                $('input[name=received_by]').val('')
                $('.receiver').addClass('is-invalid')
            }
        })

        loadDetails({
            selector: '.deliverer',
            url: '/sale-distributors?type=deliverer',
            select: (event, ui) => {
                $('input[name=delivered_by]').val(ui.item.data.id)
                $('.deliverer').removeClass('is-invalid')
            },
            search: (event) => {
                $('input[name=delivered_by]').val('').addClass('is-invalid')
                $('.deliverer').addClass('is-invalid')
            }
        })

        function calculateTax() {
            let totalTax = 0
            $('.tax-percent').each(function() {
                let row = $(this).closest('tr')
                if ($(this).val() != '') {
                    let unitPrice = parseFloat(row.find('.unit-price').val())
                    let quantity = parseFloat(row.find('.item-quantity').val())
                    let taxPercent = parseFloat($(this).val())
                    let tax = (unitPrice * taxPercent) / 100
                    totalTax += (tax * quantity)
                }
            })
            $('.invoice-tax').val(totalTax.toFixed(2))
        }
    </script>

@endsection
