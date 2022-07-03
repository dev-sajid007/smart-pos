@php
$productTax = optional($settings->where('title', 'Product Tax')->first())->options == 'yes';
$customerPoint = optional($settings->where('title', 'Customer Point')->first())->options == 'yes';
$hasProductRak = optional($settings->where('title', 'Product Rak In Product')->first())->options == 'yes';
$productDiscount = optional($settings->where('title', 'Product Discount')->first())->options == 'yes';
$is_default_pos_print = optional($settings->where('title', 'Sale Default Printing')->first())->options == 'pos-print';
$has_product_wise_discount = optional($settings->where('title', 'Product Discount')->first())->options == 'yes';
$has_additional_item_field = optional($settings->where('title', 'Need Additional Item Id Field')->first())->options == 'need-additional-item-id-field';
$allowSalesAvailableQuantity = optional($settings->where('title', 'Allow Sales When Stock Not Available')->first())->options == 'yes';
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

            .is-invalid {
                border-color: #f78484cc !important;
            }

            .is-valid {
                border-color: #38c18287 !important;
            }

            .profit-column {
                margin-top: -22px !important;
                padding-right: 5px !important;
                text-align: right !important;
                width: 100% !important;
                font-size: 11px !important;
                "

            }


            .input-group-text {
                width: 110px !important;
                padding-left: 5px;
            }

            .input-group-prepend,
            .height-30 {
                height: 30px !important;
            }

        </style>
    @endpush



@section('content')
    @include('admin.people.customers.quickadd', ['errors' => $errors])

    <main class="app-content">
        <form class="form-horizontal" id="saleForm" name="saleForm" method="POST"
            action="{{ route('sales.update', $sale->id) }}">
            @csrf @method('PUT')
            <div class="row form-screen">
                <div class="col-md-9">
                    @include('partials._alert_message')

                    <div class="bs-component">
                        <div class="card">
                            <h4 class="card-header bg-primary text-white">
                                <span class="float-left"><i class="fa fa-plus"></i> Add New Sale</span>
                                <span class="float-right">
                                    <a href="#" onclick="toggleFullScreen()" title="Full Screen" style="color:#fff;
                                    "><i class="fa fa-expand"></i></a>
                                </span>
                            </h4>
                            <input type="hidden" name="sale_type" value="">
                            <div class="card-body">
                                <!-- Customer and Date Section -->
                                @include('admin.sales.edit-sale.edit-filter-heading-table')
                                <!-- Product Entry Panel -->
                                @include('admin.sales.includes.prroduct-entry-panel')
                                <div class='row'>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered" id="table">
                                                <!-- table header -->
                                                <thead>
                                                    <tr>
                                                        <th width="3%">Sl.</th>
                                                        <th>Product Name</th>
                                                        <th>Product Code</th>
                                                        <th style="display: none">Product ID</th>
                                                        @if ($has_additional_item_field)
                                                            <th style="width: 10%;">Item Id</th>
                                                        @endif
                                                        <th style="width: 10%;" class="text-center">Quantity</th>
                                                        <th width="12%">Price</th>
                                                        @if ($has_product_wise_discount)
                                                            <th style="width: 10%;">Discount</th>
                                                        @endif
                                                        {{-- <th width="10%" class="text-center">Stock</th> --}}
                                                        <th width="12%">Subtotal</th>
                                                        <th width="1%"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="item-table-body">
                                                    @if (old('product_names') == true)
                                                        @foreach (old('product_names') as $key => $value)

                                                            <tr>
                                                                <td><span
                                                                        class="item-serial-counter">{{ $key + 1 }}</span>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="product_names[]"
                                                                        class="form-control product-name" autocomplete="off"
                                                                        value="{{ old('product_names')[$key] }}">
                                                                    <input type="hidden" class="product-cost"
                                                                        name="product_costs[]"
                                                                        value="{{ old('product_names')[$key] }}">
                                                                    <input type="hidden" class="product-id"
                                                                        name="product_ids[]"
                                                                        value="{{ old('product_ids')[$key] }}">
                                                                    <input type="hidden" class="profit-column-input"
                                                                        name="profit_columns[]"
                                                                        value="{{ old('profit_columns')[$key] }}">

                                                                    <p class="profit-column">
                                                                        {{ old('profit_columns')[$key] }}</p>

                                                                    <p class="product-rak-text"
                                                                        style="color: #156984; margin-top: -14px; margin-bottom: 0; font-size: 11px">
                                                                        {{ old('profit_columns')[$key] }}</p>
                                                                    <input class="product-rak-input" type="hidden"
                                                                        name="product_rak_names[]"
                                                                        value="{{ old('profit_columns')[$key] }}">

                                                                    <input type="hidden" name="product_taxes[]"
                                                                        class="tax-percent"
                                                                        value="{{ old('product_taxes')[$key] }}">
                                                                </td>
                                                                @if ($has_additional_item_field)
                                                                    <td><input type="text" name="product_item_ids[]"
                                                                            value="{{ old('product_item_ids')[$key] }}"
                                                                            class="form-control" placeholder="123"></td>
                                                                @endif
                                                                <td>
                                                                    <input type="text" name="quantities[]"
                                                                        value="{{ old('quantities')[$key] }}"
                                                                        class="form-control text-center item-quantity changesNo"
                                                                        autocomplete="off" onkeyup="itemQuantityKeyUp(this)"
                                                                        onkeypress="return IsNumeric(event);">
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="unit_prices[]"
                                                                        value="{{ old('unit_prices')[$key] }}"
                                                                        class="form-control unit-price changesNo"
                                                                        autocomplete="off" onkeyup="priceEnter(event, this)"
                                                                        onkeypress="return IsNumeric(event);">
                                                                </td>

                                                                @if ($has_product_wise_discount)
                                                                    <td>
                                                                        <input type="text" name="product_discounts[]"
                                                                            value="{{ old('product_discounts')[$key] }}"
                                                                            class="form-control product-discount changesNo"
                                                                            autocomplete="off"
                                                                            onkeyup="priceEnter(event, this)"
                                                                            onkeypress="return IsNumeric(event);">
                                                                    </td>
                                                                @else
                                                                    <td style="display: none">
                                                                        <input type="text" name="product_discounts[]"
                                                                            class="product-discount">
                                                                    </td>
                                                                @endif
                                                                <td>
                                                                    <input type="hidden" name="old_stock[]"
                                                                        class="old-available-stock"
                                                                        value="{{ old('old_stock')[$key] }}">
                                                                    <input type="text" name="stock_available[]"
                                                                        value="{{ old('stock_available')[$key] }}"
                                                                        readonly
                                                                        class="form-control text-center text-danger available-stock">
                                                                </td>

                                                                <td><input type="text" readonly name="product_sub_total[]"
                                                                        value="{{ old('product_sub_total')[$key] }}"
                                                                        class="form-control subtotal"></td>

                                                                <td>
                                                                    <button type="button" class="btn btn-danger btn-sm"
                                                                        title="Delete This Row" onclick="removeRow(this)"><i
                                                                            class="fa fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        @foreach ($sale->sale_details as $key => $detail)
                                                            <tr>
                                                                <td><span
                                                                        class="item-serial-counter">{{ $key + 1 }}</span>
                                                                </td>
                                                                <td>
                                                                    {{ $detail->product->product_name }}
                                                                    <input type="hidden" name="product_names[]"
                                                                        class="form-control product-name" autocomplete="off"
                                                                        value="{{ optional($detail->product)->product_name }}">
                                                                    <input type="hidden" class="product-cost"
                                                                        name="product_costs[]"
                                                                        {{ $detail->product_cost }}>
                                                                    <input type="hidden" class="product-id"
                                                                        name="product_ids[]"
                                                                        value="{{ $detail->fk_product_id }}">
                                                                    <input type="hidden" class="profit-column-input"
                                                                        name="profit_columns[]"
                                                                        value="{{ ($detail->unit_price - $detail->product_cost) * $detail->quantity }}">
                                                                    <p class="profit-column"></p>
                                                                    <p class="product-rak-text"
                                                                        style="color: #156984; margin-top: -14px; margin-bottom: 0; font-size: 11px">
                                                                    </p>

                                                                    <p class="item-serial-lists"
                                                                        style="margin-top: 20px !important; margin-bottom: 0;">
                                                                        @foreach ($detail->serials as $item)
                                                                            <input type="hidden"
                                                                                name="product_serials[{{ $detail->product->id }}][]"
                                                                                value="${ serial }">
                                                                            <span
                                                                                class="item-serial badge badge-primary mr-1"
                                                                                style="font-size: 12px">{{ $item->serial }}<span
                                                                                    class="d-print-none"
                                                                                    onMouseOver="this.style.color='red'"
                                                                                    onMouseOut="this.style.color='white'"
                                                                                    onClick="SerialItem(this)"><i
                                                                                        class="fa fa-times-circle"></i></span></span>
                                                                        @endforeach
                                                                    </p>
                                                                </td>
                                                                @if ($has_additional_item_field)
                                                                    <td>
                                                                        <input class="form-control"
                                                                            placeholder="id1, id2, id3...."
                                                                            value="{{ $detail->product_item_ids }}"
                                                                            name="product_item_ids[]">
                                                                    </td>
                                                                @endif
                                                                <td>{{ $detail->product->product_code }}</td>
                                                                <td>
                                                                    <input type="text" name="quantities[]"
                                                                        class="form-control text-center item-quantity changesNo"
                                                                        value="{{ $detail->quantity }}"
                                                                        autocomplete="off" onkeyup="itemQuantityKeyUp(this)"
                                                                        onkeypress="return IsNumeric(event);">
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="unit_prices[]"
                                                                        class="form-control unit-price changesNo"
                                                                        value="{{ $detail->unit_price }}"
                                                                        autocomplete="off" onkeyup="priceEnter(event, this)"
                                                                        onkeypress="return IsNumeric(event);">
                                                                </td>

                                                                <td><input type="text" readonly name="product_sub_total[]"
                                                                        value="{{ $detail->product_sub_total }}"
                                                                        class="form-control subtotal"></td>
                                                                <td>
                                                                    <button type="button" class="btn btn-danger btn-sm"
                                                                        title="Delete This Row" onclick="removeRow(this)"><i
                                                                            class="fa fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
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

    <input type="hidden" class="customer_due_limit"
        value="{{ optional($customers->where('is_default', 1)->first())->due_limit ?? 999999999999999 }}">
    <input type="hidden" class="check-has-additional-item-field" value="{{ $has_additional_item_field ? 'yes' : 'no' }}">
    <input type="hidden" class="check-allow-sale-available-quantity"
        value="{{ $allowSalesAvailableQuantity ? 'yes' : 'no' }}">
    <input type="hidden" class="has-product-rak" value="{{ $hasProductRak ? 'yes' : 'no' }}">
    <input type="hidden" class="has-product-discount" value="{{ $has_product_wise_discount ? 'yes' : 'no' }}">

    <!-- customer point -->
    <input type="hidden" class="customer-point-type">
    <input type="hidden" class="customer-amount-of">
    <input type="hidden" class="customer-amount">
@endsection

@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{ asset('assets/admin/jq/customerQuickAdd.js') }}"></script>
    <script src="{{ asset('jq/loadDetails.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/custom_js/toggle-full-screen.js') }}"></script>

    <script type="text/javascript">
        var customer_id = 1212;
        let default_customer_id = ''

        function loadProducts() {
            loadDetails({
                type: 'nameWithQuantity',
                selector: '.product-name',
                url: '/search-product-by-customer-id/' + ($('.selected-customer').val() | 'dummy') + '/warehouse/' +
                    $('.select-warehouse').val(),
                select: function(event, ui) {
                    const product = ui.item.data;
                    let is_add_item = true
                    if ($('.selected-customer').val() == '') {
                        is_add_item = false
                        alert('Please Select Customer')
                        return false
                    }
                    $('.product-id').each(function() {
                        if ($(this).val() == product['id']) {
                            is_add_item = false
                            return false
                        }
                    });
                    if (is_add_item == true) {
                        let row = $(event.target).closest('tr')
                        setProductInfo(row, product)
                    } else {
                        alert('This product is already added')
                    }
                    calculateTotal('please touch')
                },
                search: function(event) {
                    let row = $(event.target).closest('tr')
                    row.find('.product-id').val('')
                    row.find('.product-rak-input').val('')
                    row.find('.available-stock').val('')
                    row.find('.item-quantity').val('')
                    row.find('.unit-price').val('')
                    row.find('.tax-percent').val('')
                    row.find('.subtotal').val('')

                    calculateTotal('please touch')
                }
            });
        }
        $('.save-btn').click(function() {
            let isSubmit = true

            if ($('.receive-amount').val() > 0) {
                if ($('.account-info').val() == '') {
                    isSubmit = false
                }
            }

            // if (Number($('.customer_due_limit').val() | 0) < Number($('.current-balance').val())) {

            //     alert('This customer maximum due limit is ' + Number($('.customer_due_limit').val() | 0))
            //     isSubmit = false
            //     return false
            // }

            if ($('.select-currier').val() != '' && $('.input-currier-amount').val() == '' && $(
                    '.currier-service-for-sale').val() == 'yes') {
                alert('Please currier amount')
                isSubmit = false
            } else if (isSubmit == true) {
                $('#saleForm').submit()
            } else {
                alert('Please select transaction account')
            }
        })
        // load after page load
        $(document).ready(function() {
            $('.select2').select2();
            loadProducts();
            if ($('.select-currier').val() == '') {
                $('.currier-amount').hide()
            } else {
                $('.currier-amount').show()
            }
        })

        // warehouse change event
        $('.select-warehouse').change(function() {
            loadProducts()
            $('.item-table-body').empty();
            // row_increment()
            // location.reload();
        })

        $('.select-product').change(function() {

            let productCost = $(this).find('option:selected').data('product-cost')
            let availableQty = $(this).find('option:selected').data('available-quantity')
            let hasSerial = $(this).find('option:selected').data('has-serial')
            let productTax = $(this).find('option:selected').data('tax-percent')
            // console.log(productTax)
            // console.log($(this).find('option:selected').data());

            // alert(productTax)

            $('.select-unit-cost').val(productCost)


            if (hasSerial == 1) {
                $('.select-quantity').val(0)
                $('.select-quantity').prop('readonly', true);
                $('.select-unit-cost').focus()
                $('.product-serial-section').show()

            } else {
                $('.select-available-quantity').val(availableQty)
                $('.select-quantity').prop('readonly', false);
                $('.select-quantity').focus()
                $('.product-serial-section').hide()
            }
        })

        $('.select-quantity').keypress(function(event) {
            let keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                $('.select-unit-cost').focus()
                return false;
            }
        });


        $('.select-unit-cost').keypress(function(event) {
            let keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                insertNewItem()
            }
        });

        function checkAvailableQty() {
            let availableQty = Number($('.select-available-quantity').val() | 0)
            let inputQty = Number($('.select-quantity').val() | 0)

            if (inputQty > availableQty) {
                alert('Stock not available')
                $('.select-quantity').val(0)
            }
        }

        // handle currier for sale
        $('.select-currier').change(function() {
            if ($(this).val() == '') {
                $('.currier-amount').hide()
            } else {
                $('.currier-amount').show()
            }
        })

        // warehouse change event
        $('.select-warehouse').change(function() {
            loadProducts()
            $('.item-table-body').empty();
            // row_increment()
            // location.reload();
        });

        // change bank account event
        $('.select-account').change(function() {
            if ($('.select-account').val() == '') {
                $('.account-balance').val('')
            } else {
                $('.account-balance').val($('.select-account option:selected').data('total-amount'))
            }
        })

        function SerialItem(object) {
            deleteSerialItem($(object).parent().text())
            let tr = $(object).closest('tr');
            let qty = Number(tr.find('.item-quantity').val(tr.find('.item-quantity').val() - 1));
            let price = Number(tr.find('.unit-price').val());
            let subtotal = Number(tr.find('.item-quantity').val()) * price;
            tr.find('.subtotal').val(subtotal);

            $(object).parent().remove();
            calculateTotal('dont touch my discount')
        }


        function getProductBySerial(object) {
            $.get('/product/get-product-by-product-serial', {
                serial: $(object).val()
            }, function(res) {
                if (res.name !== undefined) {

                    let serial = res.serial
                    let productId = res.id
                    let productName = res.name
                    let productCode = res.code
                    let productPrice = res.price
                    let productTax = res.tax
                    let quantity = 1
                    let serial_count = 0
                    $('.item-serial').each(function(index, item) {
                        if (serial == $(item).text()) {
                            serial_count++
                            return false
                        }
                    })
                    if (serial_count == 0) {
                        let name = `product_names[${ productId }]`

                        if ($('input[name="' + name + '"]').length == 1) {
                            let product_serial =
                                `<span class="item-serial badge badge-primary mr-1" style="font-size: 12px">${ serial }<span class="d-print-none" onMouseOver="this.style.color='red'" onMouseOut="this.style.color='white'" onClick="SerialItem(this)"><i class="fa fa-times-circle" ></i></span></span>`
                            $('input[name="' + name + '"]').closest('td').find('.item-serial-lists').append(
                                product_serial)

                            let input_serial = '<input type="hidden" name="product_serials[' + productId +
                                '][]" value="' + serial + '">'
                            $('input[name="' + name + '"]').closest('td').find('.item-serial-lists').append(
                                input_serial)

                            let totalSerialQty = $('input[name="' + name + '"]').closest('td').find('.item-serial')
                                .length
                            let subTotal = productPrice * totalSerialQty


                            $('input[name="' + name + '"]').closest('tr').find('.item-quantity').val(totalSerialQty)
                            $('input[name="' + name + '"]').closest('tr').find('.subtotal').val(subTotal)

                        } else {

                            let additional_items_field = ''
                            let product_discount = ''

                            if ($('.check-has-additional-item-field').val() == 'yes') {
                                additional_items_field =
                                    `<td><input type="text" name="product_item_ids[${ productId }]" value="" class="form-control" placeholder="123"></td>`
                            }

                            if ($('.has-product-discount').val() == 'yes') {
                                product_discount = `<td>
                                                    <input type="text" name="product_discounts[${ productId }]" class="form-control product-discount changesNo" autocomplete="off" onkeyup="priceEnter(event, this)" onkeypress="return IsNumeric(event);">
                                                </td>`
                            } else {
                                product_discount = `<td style="display: none">
                                                    <input type="text" name="product_discounts[${ productId }]" class="product-discount">
                                                </td>`
                            }

                            let new_row = `<tr>
                                    <td><span class="item-serial-counter" ></span></td>
                                    <td>
                                        ${ productName }
                                        <input type="hidden" name="product_names[${ productId }]" class="form-control product-name" value="${ productName }" autocomplete="off">
                                        <input type="hidden" name="profit_columns[${ productId }]" class="profit-column-input"  value="">
                                        <input type="hidden" name="product_taxes[${ productId }]" class="tax-percent"  value="${productTax}">
                                        <input type="hidden" name="product_rak_names[${ productId }]" class="product-rak-input" value="">
                                        <input type="hidden" name="has_serials[${ productId }]" value="1">
                                        <p class="profit-column"></p>
                                        <p class="product-rak-text" style="color: #156984; margin-top: -14px; margin-bottom: 0; font-size: 11px"></p>
                                        <p class="item-serial-lists" style="margin-top: 20px !important; margin-bottom: 0;"  >
                                            <input type="hidden" name="product_serials[${ productId }][]" value="${ serial }">
                                            <span class="item-serial badge badge-primary mr-1" style="font-size: 12px">${ serial }<span class="d-print-none" onMouseOver="this.style.color='red'" onMouseOut="this.style.color='white'" onClick="SerialItem(this)"><i class="fa fa-times-circle"></i></span></span>
                                            
                                        </p>

                                    </td> 
                                    <td>${ productCode }</td>`

                                +
                                additional_items_field +

                                `<td>
                                        <input type="text" name="quantities[${ productId }]" readonly class="form-control text-center item-quantity" value="${ quantity }">
                                    </td>

                                    <td>
                                        <input type="text" name="unit_prices[${ productId }]" class="form-control unit-price changesNo" autocomplete="off" onkeyup="priceEnter(event, this)" value="${ productPrice }" onkeypress="return IsNumeric(event);">
                                    </td>`

                                +
                                product_discount +

                                `

                                    <td>
                                        <input type="text" name="product_sub_total[${ productId }]" class="form-control subtotal" value="${ quantity * productPrice }" readonly>
                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" title="Delete This Row" onclick="removeRow(this)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>';
                                </tr>`

                            $('.item-table-body').append(new_row);
                        }
                        $(object).val('')
                        $(".product-name").focus()
                        setItemSerial()
                        calculateTotal('dont touch my discount')
                    }

                }

            })
        }



        function setCustomerBalance(object) {
            let customer = $('.select-customer option:selected')
            $('.previous-due').val(customer.data('current-balance'))
            $('.customer_due_limit').val(customer.data('due-limit'))
        }


        // function getCustomerBalance(customer_id) {
        //     let baseUrl = "/get-customer-balance/" + customer_id
        //     $('.customer_due_limit').val(999999999)
        //     $.ajax({
        //         url: baseUrl,
        //         type: 'GET',
        //         success: function (res) {
        //             $('.previous-due').val(res.previous_due)
        //             $('.advanced-payment').val(res.advanced_payment)
        //             $('.customer_due_limit').val(res.due_limit)

        //             // customer point set
        //             setCustomerPointInfo(res)

        //             calculateTotal('please touch')
        //         }
        //     })
        // }

        function setCustomerPointInfo(res) {
            $('.customer-point-type').val(res.type)
            $('.customer-amount-of').val(res.amount_of)
            $('.customer-amount').val(res.amount)
        }

        function saveSaleInvoice() {
            let isSubmit = true
            if ($('.receive-amount').val() > 0) {
                if ($('.account-info').val() == '') {
                    isSubmit = false
                }
            }
            if ($('.default-customer-id').val() == $('.selected-customer').val()) {
                if ($('.current-balance').val() > 0) {
                    alert('Can not be due for default customer')
                    isSubmit = false
                    return false
                }
            }
            $('.item-quantity').each(function() {
                if ($(this).val() == '' || $(this).val() == 0) {
                    alert('Product quantity can not be zero or empty')
                    isSubmit = false
                    return false
                }
            })

            // if (Number($('.customer_due_limit').val() | 0) < Number($('.current-balance').val())) {
            //     alert('This customer maximum due limit is ' + Number($('.customer_due_limit').val() | 0))
            //     isSubmit = false
            //     return false
            // }


            if ($('.select-currier').val() != '' && $('.input-currier-amount').val() == '') {
                alert('Please currier amount')
                isSubmit = false
            } else if (isSubmit == true) {
                $('#saleForm').submit()
            } else {
                alert('Please select transaction account')
            }
        }
        // add new item row when click on add more button
        function row_increment() {

            let i = $('table tr').length;

            let additional_items_field = ''
            let product_discount = ''

            if ($('.check-has-additional-item-field').val() == 'yes') {
                additional_items_field =
                    `<td><input type="text" name="product_item_ids[]" value="" class="form-control" placeholder="123"></td>`
            }

            if ($('.has-product-discount').val() == 'yes') {
                product_discount = `<td>
                                    <input type="text" name="product_discounts[]" class="form-control product-discount changesNo" autocomplete="off" onkeyup="priceEnter(event, this)" onkeypress="return IsNumeric(event);">
                                </td>`
            } else {
                product_discount = `<td style="display: none">
                                    <input type="text" name="product_discounts[]" class="product-discount">
                                </td>`
            }

            let new_row = `<tr>
                    <td><span class="item-serial-counter"></span></td>

                    <td>
                        <input type="text" name="product_names[]" class="form-control product-name" autocomplete="off">
                        <input type="hidden" class="product-cost" name="product_costs[]">
                        <input type="hidden" class="product-id" name="product_ids[]">
                        <input type="hidden" class="profit-column-input" name="profit_columns[]">

                        <p class="profit-column" ></p>


                        <input type="hidden" class="has-box-piece" value="0">
                        <input type="hidden" class="box-qty" value="1">
                        <input type="hidden" class="piece-qty" value="1">
                        <input type="hidden" class="total-box-qty" value="0">
                        <input type="hidden" class="total-piece-qty" value="0">

                        <p class="product-rak-text" style="color: #156984; margin-top: -14px; margin-bottom: 0; font-size: 11px"></p>
                        <input class="product-rak-input" type="hidden" name="product_rak_names[]">

                        <input type="hidden" name="product_taxes[]" class="tax-percent">
                    </td>`

                +
                additional_items_field +

                `<td>
                        <input type="text" name="quantities[]" onkeyup="itemQuantityKeyUp(this)" class="form-control text-center item-quantity changesNo" autocomplete="off" onkeypress="return IsNumeric(event);">
                    </td>

                    <td>
                        <input type="text" name="unit_prices[]" class="form-control unit-price changesNo" autocomplete="off" onkeyup="priceEnter(event, this)" onkeypress="return IsNumeric(event);">
                    </td>`

                +
                product_discount +

                `<td>
                        <input type="text" name="stock_available[]" class="form-control available-stock text-center text-danger" readonly>
                    </td>

                    <td>
                        <input type="text" name="product_sub_total[]" class="form-control subtotal" readonly>
                    </td>

                    <td>
                        <button type="button" class="btn btn-danger btn-sm" title="Delete This Row" onclick="removeRow(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>';
                </tr>`

            $('.item-table-body').append(new_row);
            $(".product-name").focus()
            i++;
            setItemSerial()
            calculateTotal('dont touch my discount')
        }

        function priceEnter(e, object) {
            if (e.keyCode == 13) {
                row_increment()
            } else {
                calculateProductProfit(object)
            }
        }

        function itemQuantityKeyUp(object) {
            calculateProductProfit(object)
        }

        function calculateProductProfit(object) {

            let purchase_price = $(object).closest('tr').find('.product-cost').val()
            let sale_price = $(object).closest('tr').find('.unit-price').val()
            let item_quantity = $(object).closest('tr').find('.item-quantity').val()

            if (purchase_price != '' && sale_price != '' && item_quantity != '') {
                let profit_per_item = Number(sale_price) - Number(purchase_price)

                let profit = (item_quantity * profit_per_item).toFixed(1)
                $(object).closest('tr').find('.profit-column').text(profit)
                $(object).closest('tr').find('.profit-column-input').val(profit)
            } else {
                $(object).closest('tr').find('.profit-column').text('')
                $(object).closest('tr').find('.profit-column-input').val('')
            }
        }


        function removeRow(el) {
            $(el).parents("tr").remove()
            calculateTotal('please touch')
            setItemSerial()
        }


        function setProductInfo(row, product) {
            let price = product['customer_price']
            let product_cost = product['product_cost']
            let subtotal = 0

            row.find('.product-id').val(product['id'])
            row.find('.product-name').val(product['name'])
            row.find('.unit-price').val(price)
            row.find('.available-stock').val(product['retail_quantity'])
            row.find('.tax-percent').val(product['tax'])
            row.find('.product-discount').val(product['discount'])

            if ($('.check-allow-sale-available-quantity').val() != 'yes') {
                row.find('.item-quantity').val(0)
            } else {
                row.find('.item-quantity').val(1)
                subtotal = price
            }

            row.find('.subtotal').val(subtotal)
            row.find('.profit-column').text((Number(price) - Number(product_cost)).toFixed(1))
            row.find('.profit-column-input').val((Number(price) - Number(product_cost)).toFixed(1))
            row.find('.product-cost').val(Number(product_cost))


            if ($('.has-product-rak').val() == 'yes') {
                row.find('.product-rak-text').text(product['product_rak_name'])
                row.find('.product-rak-input').val(product['product_rak_name'])
            }
        }

        if ($('.check-allow-sale-available-quantity').val() != 'yes') {
            $(document).on('keyup', '.item-quantity', function() {
                let quantity = $(this).val()
                let stock = $(this).closest('tr').find('.available-stock').val()

                if (parseFloat(quantity) > parseFloat(stock)) {
                    alert('There are no available stock, Please Purchase this product.')
                    $(this).val('0')
                    calculateTotal('please touch')
                    return false;
                }
            })
        }

        //price change
        $(document).on('keyup', '.changesNo', function() {
            let row = $(this).closest('tr')
            let quantity = row.find('.item-quantity').val()
            let price = row.find('.unit-price').val()
            let subtotal = 0

            row.find('.subtotal').val((parseFloat(price) * parseFloat(quantity)).toFixed(2))
            itemQuantityKeyUp(this)
            calculateTotal('please touch')
        });

        $('.receive-amount, .input-currier-amount').keyup(function() {
            calculateTotal('dont touch my discount')
        })

        $(document).on('keyup', '.discount-amount', function() {
            let discount_amount = $(this).val()
            let percent = 0


            if (discount_amount != '') {
                percent = (Number(discount_amount) * 100) / (Number($('.invoice-total').val() | 0))
            }
            $('.discount-percent').val(percent.toFixed(2))
            calculateTotal('dont touch my discount')
        });

        $(document).on('keyup', '.discount-percent', function() {
            let discount_percent = $(this).val()
            let amount = 0
            if (discount_percent != '') {
                amount = Math.ceil((Number(discount_percent) * ($('.invoice-total').val() | 0)) / 100)
            }
            $('.discount-amount').val(amount.toFixed(2))
            calculateTotal('dont touch my discount')
        });


        //total price calculation
        function calculateTotal(dont_touch_discount) {
            let invoice_total = 0;
            let total_payable_amount = 0;
            let invoice_tax = 0
            let invoice_discount = parseFloat($('.discount-amount').val() | 0)
            let cod_amount = Math.round($('.cod-amount').val() | 0)
            let product_wise_discount = 0
            let previous_due = parseFloat($('.previous-due').val() | 0)
            let advanced_payment = parseFloat($('.advanced-payment').val() | 0)
            let receive_amount = parseFloat($('.receive-amount').val() | 0)
            let currier_amount = parseFloat($('.input-currier-amount').val() | 0)
            let payable_amount = 0



            $('.subtotal').each(function(index, item) {
                let subtotal = $(item).val()
                let quantity = ($(item).closest('tr').find('.item-quantity').val() | 0)
                let discount = ($(item).closest('tr').find('.product-discount').val() | 0)
                product_wise_discount += (Number(quantity) * Number(discount))

                if (subtotal != '') {
                    let tax_percent = ((($(item).closest('tr').find('.tax-percent').val()) * Number(subtotal)) /
                        100) || 0;
                    invoice_tax += Number(tax_percent);
                    invoice_total += Number(subtotal)
                }
            });


            if (dont_touch_discount != 'dont touch my discount') {
                invoice_discount = product_wise_discount
                $('.discount-amount').val(product_wise_discount)
                let percent = (Number(product_wise_discount) * 100) / (Number(invoice_total | 0))
                $('.discount-percent').val(percent.toFixed(2))
            }

            $('.invoice-tax').val(invoice_tax)
            $('.invoice-total').val(invoice_total)
            payable_amount = (invoice_total + invoice_tax + currier_amount - invoice_discount + cod_amount)
            total_payable_amount = (previous_due + payable_amount - advanced_payment)
            $('.payable-amount').val(payable_amount)
            $('.total-payable-amount').val(total_payable_amount)
            $('.current-balance').val(Number(total_payable_amount) - Number(receive_amount))
            setCustomerPaymentAmount(payable_amount)
        }


        function setCustomerPaymentAmount(payable_amount) {
            let customer_point_type = $('.customer-point-type').val()

            if ($('.customer-point-type').val() != 'nothing') {
                let amount_of = $('.customer-amount-of').val()
                let amount = $('.customer-amount').val()

                if (payable_amount >= amount_of && amount_of > 0) {
                    if (customer_point_type == 'amount') {
                        let point_amount = parseInt(payable_amount / amount_of) * amount
                        $('.customer-point').val(point_amount)
                    } else {
                        let point_amount = (payable_amount * amount) / 100
                        $('.customer-point').val(point_amount)
                    }
                } else {
                    $('.customer-point').val('')
                }
            }
        }


        function setItemSerial() {
            $('.item-table-body tr').each(function(counter) {
                $(this).find('.item-serial-counter').text(counter + 1)
            })
        }


        //It restrict the non-numbers
        var specialKeys = new Array();
        specialKeys.push(8, 46); //Backspace

        function IsNumeric(e) {
            var keyCode = e.which ? e.which : e.keyCode;
            var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
            return ret;
        }


        function calculateCODAmount(object) {
            let invoiceTotal = Number($('.invoice-total').val() | 0)
            let invoiceTax = Number($('.invoice-tax').val() | 0)
            let currierAmount = Number($('.input-currier-amount').val() | 0)
            let discountAmount = Number($('.discount-amount').val() | 0)

            let codPercent = Number($(object).val() | 0)

            let pTotal = invoiceTotal + invoiceTax + currierAmount - discountAmount;

            if (pTotal > 0 && codPercent > 0) {
                let amount = Math.round((pTotal * codPercent) / 100)
                $('.cod-amount').val(amount)
            } else {
                $('.cod-amount').val('')
            }
            calculateTotal('dont touch my discount')
        }


        // add new item row when click on add more button
        function insertNewItem() {

            let selected_item = $('.select-product').find('option:selected');
            let productId = selected_item.val()
            let hasSerial = selected_item.data('has-serial')
            let productName = selected_item.data('product-name')
            let productCode = selected_item.data('product-code')
            let availableQty = selected_item.data('available-quantity')
            let productPrice = $('.select-unit-cost').val()
            let quantity = $('.select-quantity').val()
            let taxProduct = selected_item.data('tax-percent')

            let additional_items_field = ''
            let product_discount = ''

            if ($('.check-has-additional-item-field').val() == 'yes') {
                additional_items_field =
                    `<td><input type="text" name="product_item_ids[${ productId }]" value="" class="form-control" placeholder="123"></td>`
            }

            if ($('.has-product-discount').val() == 'yes') {
                product_discount = `<td>
                                    <input type="text" name="product_discounts[${ productId }]" class="form-control product-discount changesNo" autocomplete="off" onkeyup="priceEnter(event, this)" onkeypress="return IsNumeric(event);">
                                </td>`
            } else {
                product_discount = `<td style="display: none">
                                    <input type="text" name="product_discounts[${ productId }]" class="product-discount">
                                </td>`
            }
            let new_row = `<tr>
                    <td><span class="item-serial-counter"></span></td>

                    <td>
                        ${ productName }
                        <input type="hidden" name="product_names[${ productId }]" class="form-control product-name" value="${ productName }" autocomplete="off">
                        <input type="hidden" name="profit_columns[${ productId }]" class="profit-column-input" value="">
                        <input type="hidden" name="product_taxes[${ productId }]" class="tax-percent" value="${taxProduct}">
                        <input type="hidden" name="product_rak_names[${ productId }]" class="product-rak-input" value="">
                        <input type="hidden" name="has_serials[${ productId }]" value="">
                        <p class="profit-column"></p>
                        <p class="product-rak-text" style="color: #156984; margin-top: -14px; margin-bottom: 0; font-size: 11px"></p>
                    </td>
                    <td>${ productCode }</td>
                    ` +
                additional_items_field +
                `<td>
                        <input type="text" name="quantities[${ productId }]" onkeyup="itemQuantityKeyUp(this)" class="form-control text-center item-quantity" value="${ quantity }" autocomplete="off" onkeypress="return IsNumeric(event);">
                    </td>
                    <td>
                        <input type="text" name="unit_prices[${ productId }]" class="form-control unit-price changesNo" autocomplete="off" onkeyup="priceEnter(event, this)" value="${ productPrice }" onkeypress="return IsNumeric(event);">
                    </td>` +
                product_discount +
                `
                    <td>
                        <input type="text" name="product_sub_total[${ productId }]" class="form-control subtotal" value="${ quantity * productPrice }" readonly>
                    </td>

                    <td>
                        <button type="button" class="btn btn-danger btn-sm" title="Delete This Row" onclick="removeRow(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>';
                </tr>`

            $('.item-table-body').append(new_row);
            $(".product-name").focus()
            setItemSerial()
            calculateTotal('dont touch my discount')
            $('.cod-percent').val(0);
            $('.cod-amount').val('');

        }


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

        function deleteSerialItem(serialName) {
            $.ajax({
                url: '/product/serial-product',
                type: "POST", // data type (can be get, post, put, delete)
                data: {
                    serial: serialName,
                    _token:"{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

    </script>

@endsection
