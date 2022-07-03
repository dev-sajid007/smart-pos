
@php
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

@section('title', ' - Package Sale')

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

    <main class="app-content">
        <form class="form-horizontal" id="saleForm" name="saleForm" method="POST" action="{{ route('package-sales.store') }}">
            @csrf
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
                                <div class="row">
                                    <div class="col-sm-12">
                                        @include('admin.sales.package-sales.filter-heading-table')
                                    </div>

                                    <br>

                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="40%">Package Name</th>
                                                    <th width="20%">Quantity</th>
                                                    <th width="20%">Unit Cost</th>
                                                </tr>
                                            </thead>
                                
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select class="form-control select-product select2 mr-0" onchange="selectPackageProduct(this)" style="width: 100%">
                                                            <option value="">Select</option>
                                                            @foreach($productPackages as $key => $product)
                                                                <option value="{{ $product->id }}"
                                                                        data-product-name="{{ $product->name }}"
                                                                        data-product-cost="{{ $product->price }}"
                                                                    >
                                                                    {{ $product->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input class="select-quantity form-control" onkeypress="return selectProductQuantity(event)" type="text" value="">
                                                    </td>
                                                    <td>
                                                        <input class="select-unit-cost form-control"  onkeypress="return productPriceEnterKeyEvent(event)" type="text" value="">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered" id="table">
                                                <!-- table header -->
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" width="5%">Sl.</th>
                                                        <th width="40%">Product Name</th>
                                                        <th style="width: 10%;" class="text-center">Quantity</th>
                                                        <th class="text-right" width="12%">Price</th>
                                                        <th class="text-right" width="12%">Subtotal</th>
                                                        <th width="3%"></th>
                                                    </tr>
                                                </thead>

                                                <tbody class="item-table-body">
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('admin.sales.package-sales.right-side-total-calculation')
            </div>
        </form>
    </main>

    <input type="hidden" class="customer_due_limit" value="{{ optional($customers->where('default', 1)->first())->due_limit ?? 999999999999999 }}">
    <input type="hidden" class="check-has-additional-item-field" value="{{ $has_additional_item_field ? 'yes' : 'no' }}">
    <input type="hidden" class="check-allow-sale-available-quantity" value="{{ $allowSalesAvailableQuantity ? 'yes' : 'no' }}">
    <input type="hidden" class="has-product-rak" value="{{ $hasProductRak ? 'yes' : 'no' }}">
    <input type="hidden" class="has-product-discount" value="{{ $has_product_wise_discount ? 'yes' : 'no' }}">

    <!-- customer point -->
    <input type="hidden" class="customer-point-type"> 
    <input type="hidden" class="customer-amount-of"> 
    <input type="hidden" class="customer-amount"> 
@endsection

@section('js')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



<script type="text/javascript" src="{{ asset('assets/custom_js/toggle-full-screen.js') }}"></script>

<script type="text/javascript">

    

    // load after page load
    $(document).ready(function () {
        $('.select2').select2();

    })

    function selectPackageProduct(obejct) 
    {
        let productCost = $(obejct).find('option:selected').data('product-cost')
        $('.select-unit-cost').val(productCost)
        $('.select-product').select2();
        $('.select-quantity').focus()
    }

    function selectProductQuantity(evnt)
    {
        let keycode = (evnt.keyCode ? evnt.keyCode : evnt.which);
        if(keycode == '13') {
            evnt.preventDefault()
            $('.select-unit-cost').focus()
            // return false;
        }
        return keycode >= 46 && keycode <= 57
    }


    function productPriceEnterKeyEvent(evnt) 
    {
        let keycode = (evnt.keyCode ? evnt.keyCode : evnt.which);
        if(keycode == '13') {
            insertNewItem()
        }
        return keycode >= 46 && keycode <= 57
    }
    
    // handle currier for sale
    function selectCourier(object) {
        if ($(object).val() == '') {
            $('.currier-amount').hide()
        } else {
            $('.currier-amount').show()
        }
    }


    // change bank account event
    $('.select-account').change(function () {
        if ($('.select-account').val() == '') {
            $('.account-balance').val('')
        } else {
            $('.account-balance').val($('.select-account option:selected').data('total-amount'))
        }
    })

    function selectCustomer()
    {
        let customer = $('.select-customer option:selected')
        let previous_due = 0
        let advance      = 0

        $('.customer_due_limit').val(999999999999)

        if (customer.val() != '') {
            $('.customer_due_limit').val(customer.data('due-limit'))
            
            let balance = customer.data('balance')
            if (balance < 0) {
                previous_due = 0
                advance      = (-1) * Number(balance) 
            } else {
                previous_due = Number(balance)
                advance      = 0 
            }
        }
        $('.previous-due').val(previous_due)
        $('.advanced-payment').val(advance)
    }

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



        if (Number($('.customer_due_limit').val() | 0) < Number($('.current-balance').val())) {
            alert('This customer maximum due limit is ' + Number($('.customer_due_limit').val() | 0))
            isSubmit = false
            return false
        }


        if ($('.select-currier').val() != '' && $('.input-currier-amount').val() == '') {
            alert('Please currier amount')
            isSubmit = false
        }

        else if (isSubmit == true) {
            $('#saleForm').submit()
        } else {
            alert('Please select transaction account')
        }
    }




    function removeRow(el) {
        $(el).parents("tr").remove()
        calculateTotal('please touch')
        setItemSerial()
    }



    $('.receive-amount, .input-currier-amount').keyup(function () {
        calculateTotal('dont touch my discount')
    })

    $(document).on('keyup', '.discount-amount', function () {
        let discount_amount = $(this).val()
        let percent = 0


        if (discount_amount != '') {
            percent = (Number(discount_amount) * 100) / (Number($('.invoice-total').val() | 0))
        }
        $('.discount-percent').val(percent.toFixed(2))
        calculateTotal('dont touch my discount')
    });

    $(document).on('keyup', '.discount-percent', function () {
        let discount_percent = $(this).val()
        let amount  = 0
        if (discount_percent != '') {
            amount = (Number(discount_percent) * ($('.invoice-total').val() | 0)) / 100
        }
        $('.discount-amount').val(amount.toFixed(2))
        calculateTotal('dont touch my discount')
    });


    //total price calculation
    function calculateTotal(dont_touch_discount) {
        let invoice_total           = 0;
        let invoice_tax             = 0
        let invoice_discount        = parseFloat($('.discount-amount').val() | 0)
        let product_wise_discount   = 0
        let previous_due            = parseFloat($('.previous-due').val() | 0)
        let advanced_payment        = parseFloat($('.advanced-payment').val() | 0)
        let receive_amount          = parseFloat($('.receive-amount').val() | 0)
        let currier_amount          = parseFloat($('.input-currier-amount').val() | 0)
        let payable_amount          = 0
        let total_payable_amount    = 0;



        $('.subtotal').each(function (index, item) {
            let subtotal = $(item).text()
            invoice_total += Number(subtotal | 0)
        });
        $('.invoice-total').val(invoice_total)

        if (dont_touch_discount != 'dont touch my discount') {
            invoice_discount = product_wise_discount
            $('.discount-amount').val(product_wise_discount)
            let percent = (Number(product_wise_discount) * 100) / (Number(invoice_total | 0))
            $('.discount-percent').val(percent.toFixed(2))
        }

        $('.invoice-tax').val(invoice_tax)
        payable_amount = (invoice_total + invoice_tax + currier_amount - invoice_discount)
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


    function setItemSerial()
    {
        $('.item-table-body tr').each(function (index) {
            $(this).find('.item-serial').text(index + 1)
        })

        calculateTotal('dont touch my discount')
    }





    // add new item row when click on add more button
    function insertNewItem() {

        let tableTobody = $('.item-table-body')

        let quantity    = $('.select-quantity').val()
        let package     = $('.select-product option:selected')
        let price       = $('.select-unit-cost').val()

        $('.item-table-body tr')
        
        let tr =    `<tr>
                        <td class="item-serial text-center"></td>
                        <td>
                            <input type="hidden" class="package-id" name="package_ids[]" value="${ package.val() }">
                            ${ package.text() }
                        </td>    
                        <td class="text-center">
                            <input type="hidden" class="package-quantity" name="quantities[]" value="${ quantity }">
                            ${ quantity }
                        </td>    
                        <td class="text-right">
                            <input type="hidden" class="package-price" name="prices[]" value="${ price }">
                            ${ price }
                        </td>    
                        <td class="text-right subtotal">${ Number(quantity) * Number(price) }</td>   
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm" title="Remove This Row" onclick="removeRow(this)">
                                <i class="fa fa-times"></i>
                            </button>
                        </td> 
                    </tr>`


        $('.item-table-body').append(tr)

        setItemSerial()
    }


</script>

@endsection
