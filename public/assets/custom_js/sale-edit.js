

let default_customer_id = ''
let customer_id         = 1212;
let count               = 0


// load after page load
$(document).ready(function () {
    $('.select2').select2();
    loadProducts()
})



// handle currier for sale
$('.select-currier').change(function () {
    if ($(this).val() == '') {
        $('.currier-amount').hide()
    } else {
        $('.currier-amount').show()
    }
})

// warehouse change event
$('.select-warehouse').change(function () {
    loadProducts()
    $('.item-table-body').empty();
    row_increment()
})

// change bank account event
$('.select-account').change(function () {
    if ($('.select-account').val() == '') {
        $('.account-balance').val('')
    } else {
        $('.account-balance').val($('.select-account option:selected').data('total-amount'))
    }
})




jQuery(function ($) {
    $("#customer").autocomplete({
        source: "/get-customer",
        autoFocus: true,
        select: function (key, value) {
            $('.selected-customer').val(value.item.id)
            $('#customer').val(value.item.value)
            $('#id').val(value.item.id)
            $('.previous-due').val(Math.abs(value.item.previous_due))
            $('.advanced-payment').val(value.item.advanced_payment)
            getCustomerBalance(value.item.id)

            customer_id = value.item.id
            loadProducts()
        }
    })
})

function getCustomerBalance(customer_id) {

    // if ($('.old-customer-id').val() != customer_id) {
        let baseUrl = "/get-customer-balance/" + customer_id
        $('.customer_due_limit').val(999999999)
        $.ajax({
            url: baseUrl,
            type: 'GET',
            success: function (res) {
                $('.previous-due').val(res.previous_due)
                $('.advanced-payment').val(res.advanced_payment)
                $('.customer_due_limit').val(res.due_limit)
                // customer point set
                setCustomerPointInfo(res)
                if (count > 0) {
                    calculateTotal('please touch')
                }
                count++
            }
        })
    // }
}



function setCustomerPointInfo(res) {
    $('.customer-point-type').val(res.type)
    $('.customer-amount-of').val(res.amount_of)
    $('.customer-amount').val(res.amount)
}

$('.save-btn').click(function () {
    let isSubmit = true

    if ($('.receive-amount').val() > 0) {
        if ($('.account-info').val() == '') {
            isSubmit = false
        }
    }

    if (Number($('.customer_due_limit').val() | 0) < Number($('.current-balance').val())) {

        alert('This customer maximum due limit is ' + Number($('.customer_due_limit').val() | 0))
        isSubmit = false
        return false
    }

    if ($('.select-currier').val() != '' && $('.input-currier-amount').val() == '' && $('.currier-service-for-sale').val() == 'yes') {
        alert('Please currier amount')
        isSubmit = false
    }

    else if (isSubmit == true) {
        $('#saleForm').submit()
    } else {
        alert('Please select transaction account')
    }
})


function priceEnter(e, object)
{
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

function loadProducts()
{
    let url = '/search-product-by-customer-id/' + ($('.selected-customer').val() | 'dummy') + '/warehouse/' + $('.select-warehouse').val()

    if ($('.sale-type').val() == 'holesale') {
        url = '/search-holesale-product-by-customer-id/' + ($('.selected-customer').val() | 'dummy') + '/warehouse/' + $('.select-warehouse').val()
    }
    loadDetails({
        type: 'nameWithQuantity',
        selector: '.product-name',
        url: url,
        select: function (event, ui) {
            const product = ui.item.data;

            let is_add_item = true

            if ($('.selected-customer').val() == '') {
                is_add_item = false
                alert('Please Select Customer')
                return false
            }

            $('.product-id').each(function () {
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
            if (count > 0) {
                calculateTotal('please touch')
            }
        },

        search: function (event) {
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
    })
}

function setProductInfo(row, product) {
    let price       = product['customer_price']
    let product_cost = product['product_cost']
    let subtotal    = 0

    row.find('.product-id').val(product['id'])
    row.find('.product-name').val(product['name'])
    row.find('.unit-price').val(price)
    row.find('.available-stock').val(product['retail_quantity'])
    row.find('.tax-percent').val(product['tax'])
    row.find('.product-discount').val(product['discount'])

    if($('.check-allow-sale-available-quantity').val() != 'yes') {
        row.find('.item-quantity').val(0)
    } else {
        row.find('.item-quantity').val(1)
        subtotal = price
    }
    row.find('.subtotal').val(subtotal)
    row.find('.profit-column').text((Number(price) - Number(product_cost)).toFixed(1))
    row.find('.profit-column-input').val((Number(price) - Number(product_cost)).toFixed(1))
    row.find('.product-cost').val(Number(product_cost))


    if($('.has-product-rak').val() == 'yes') {
        row.find('.product-rak-text').text(product['product_rak_name'])
        row.find('.product-rak-input').val(product['product_rak_name'])
    }
}

if($('.check-allow-sale-available-quantity').val() != 'yes') {
    $(document).on('keyup', '.select-item-quantity', function () {
        let quantity = $(this).val()
        let stock = $(this).closest('tr').find('.available-stock').val()
        let old_available_stock = 0
        let old_value = $(this).closest('tr').find('.old-available-stock').val()
        if (old_value != '') {
            old_available_stock = Number(old_value)
        }

        if(parseFloat(quantity) > parseFloat(Number(stock) + old_available_stock)) {
            alert('There are no available stock, Please Purchase this product.')
            $(this).val('0')
            calculateTotal()
            return false;
        }
    })
}

//price change
$(document).on('keyup', '.changesNo', function () {
    let row = $(this).closest('tr')
    let quantity = row.find('.item-quantity').val()
    let price = row.find('.unit-price').val()
    let subtotal = 0

    row.find('.subtotal').val((parseFloat(price) * parseFloat(quantity)).toFixed(2))
    itemQuantityKeyUp(this)
    calculateTotal('please touch')
});

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
        amount = Math.ceil((Number(discount_percent) * ($('.invoice-total').val() | 0)) / 100)
    }
    $('.discount-amount').val(amount.toFixed(2))
    calculateTotal('dont touch my discount')
});


//total price calculation
function calculateTotal(dont_touch_discount) {
    // calculateTax()
    let invoice_total           = 0;
    let total_payable_amount    = 0;
    let invoice_tax             = 0
    let invoice_discount        = parseFloat($('.discount-amount').val() | 0)
    let cod_amount              = Math.round($('.cod-amount').val() | 0)
    let product_wise_discount   = 0
    let previous_due            = parseFloat($('.previous-due').val() | 0)
    let advanced_payment        = parseFloat($('.advanced-payment').val() | 0)
    let receive_amount          = parseFloat($('.receive-amount').val() | 0)
    let currier_amount          = parseFloat($('.input-currier-amount').val() | 0)
    let payable_amount          = 0



    $('.subtotal').each(function (index, item) {
        let subtotal = $(item).val()
        let quantity = ($(item).closest('tr').find('.item-quantity').val() | 0)
        let discount = ($(item).closest('tr').find('.product-discount').val() | 0)
        product_wise_discount += (Number(quantity) * Number(discount))

        if (subtotal != '') {
            let tax_percent = ((($(item).closest('tr').find('.tax-percent').val() | 0) * Number(subtotal)) / 100) | 0
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



function calculateCODAmount(object)
{
    let invoiceTotal = Number($('.invoice-total').val() | 0)
    let codPercent   = Number($(object).val() | 0)

    if (invoiceTotal > 0 && codPercent > 0) {
        let amount = Math.round((invoiceTotal * codPercent) / 100)
        $('.cod-amount').val(amount)
    } else {
        $('.cod-amount').val('')
    }
    calculateTotal('dont touch my discount')
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
    $('.item-table-body tr').each(function (counter) {
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



// add new item row when click on add more button
function row_increment() {

    let i = $('table tr').length;

    let additional_items_field = ''
    let product_discount = ''

    if($('.check-has-additional-item-field').val() == 'yes') {
        additional_items_field = `<td><input type="text" name="product_item_ids[]" value="" class="form-control" placeholder="123"></td>`
    }

    if($('.has-product-discount').val() == 'yes') {
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

                    <p class="product-rak-text" style="color: #156984; margin-top: -14px; margin-bottom: 0; font-size: 11px"></p>
                    <input class="product-rak-input" type="hidden" name="product_rak_names[]">

                    <input type="hidden" name="product_taxes[]" class="tax-percent">
                </td>`

                + additional_items_field +

                `<td>
                    <input type="text" name="quantities[]" onkeyup="itemQuantityKeyUp(this)" class="form-control text-center item-quantity changesNo" autocomplete="off" onkeypress="return IsNumeric(event);">
                </td>

                <td>
                    <input type="text" name="unit_prices[]" class="form-control unit-price changesNo" autocomplete="off" onkeyup="priceEnter(event, this)" onkeypress="return IsNumeric(event);">
                </td>`

                + product_discount +

                `<td>
                    <input type="hidden" name="old_stock[]" class="old-available-stock" value="0">
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
    i++;
    setItemSerial()
}
