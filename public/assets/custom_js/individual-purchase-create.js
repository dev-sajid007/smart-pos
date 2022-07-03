
loadDatePicker()


$('.select-product').change(function () {
    let productCost = $(this).find('option:selected').data('product-cost')
    let hasSerial = $(this).find('option:selected').data('has-serial')


    $('.select-unit-cost').val(productCost)
    $('.select-product').select2();

    if (hasSerial == 1) {
        $('.select-quantity').val(0)
        $('.select-quantity').prop('readonly', true);
        $('.select-unit-cost').focus()
    } else {
        $('.select-quantity').prop('readonly', false);
        $('.select-quantity').focus()
    }

})

$('.select-quantity').keypress(function(event) {
    let keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13') {
        $('.select-free-quantity').val(0)
        $('.select-unit-cost').focus()
        return false;
    }
});

$('.select-unit-cost').keypress(function(event) {
    let keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13') {
        $('.select-free-quantity').focus()
        // return false;
    }
});

// $('.select-free-quantity').keypress(function(event) {
//     let keycode = (event.keyCode ? event.keyCode : event.which);
//     if(keycode == '13') {
//         return false;
//     }
// });

$('.select-free-quantity').keyup(function(event) {
    let keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13') {
        insertNewItem()
    }
});

// '<td><input type="text" name="expire_dates[' + productId + ']" autocomplete="off" placeholder="dd/mm/yyyy" value="" class="form-control expire-date">';
function insertNewItem () {
    let selected_item   = $('.select-product').find('option:selected');
    let productId       = selected_item.val()
    let hasSerial       = selected_item.data('has-serial')
    let productName     = selected_item.data('product-name')
    let productCode     = selected_item.data('product-code')
    let productCost     = $('.select-unit-cost').val()
    let quantity        = $('.select-quantity').val()

    let freeQuantity    = Number($('.select-free-quantity').val() | 0)

    if (productId != '' && quantity != '') {
            let tr =    '<tr>' +
                '<td class="item-serial"></td>' +
                '<td>' + productName + '<input type="hidden" name="product_ids[' + productId + ']" class="product-id" value="' + productId + '"></td>' +
                '<td>' + productCode + '</td>';
                if (hasSerial == 1) {
                    tr += '<td>'
                        tr += '<div class="input-group">' +
                                '<input type="text" id="serial_qty_input_' + productId + '" readonly  name="quantities[' + productId + ']" class="product-quantity form-control text-center" value="' + quantity + '">' +

                                '<div class="input-group-append">' +
                                    '<span style="cursor: pointer; padding-top: 5px" data-toggle="modal" data-target="#add_product_serial_modal" onclick="showSerialProductPopup(' + productId + ')" class="btn btn-sm btn-dark" title="Add Serial">+</span>' +
                                '</div>' +
                            '</div>' +
                            '<textarea style="display:none" name="serials[' + productId + ']" id="serial_qty_textarea_' + productId + '"></textarea>' +
                        '</td>'
                } else {
                    tr += '<td><input type="hidden" name="serials[' + productId + ']"><input type="text" onclick="return onlyNumber(event)" name="quantities[' + productId + ']" class="product-quantity form-control text-center" onkeyup="changeQuantity(this)" value="' + quantity + '"></td>'
                }

            tr += '<td><input type="text" readonly name="unit_cost[' + productId + ']" class="product-cost form-control text-center" id="product_costs_' + productId + '" value="' + productCost + '"></td>' +
                '<td><input type="text" onclick="return onlyNumber(event)" name="free_quantities[' + productId + ']" class="free-quantity form-control text-center" value="' + freeQuantity + '"></td>' +
                '<td><input type="text" readonly name="product_sub_total[' + productId + ']" id="product_subtotals_' + productId + '" class="product-row-subtotal form-control text-center" value="' + (productCost * quantity) + '"></td>' +
                '<td class="text-right">' +
                '<button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="fa fa-trash"></i></button>'
            '</td>' +
        '</tr>'
        $('.product-display').append(tr)

        setItemSerial()

        // disable selected option
        $('.select-product option[value=' + productId + ']').prop("disabled", true);
        $('.select-quantity').val('');
        $('.select-product').val('');
        $('.select-product').select2('focus');
        $('.select-product').select2();
        loadDatePicker()
    } else {
        alert('Select product and fill quantity and free quantity')
    }
}

function showSerialProductPopup(product_id)
{
    $('.product-serial-textarea').val($('#serial_qty_textarea_' + product_id).val())
    $('.total-serial-count').text($('#serial_qty_input_' + product_id).val())

    $('.product-id-holder').val(product_id)
}

function addFinalizeProductSerial()
{
    product_id = $('.product-id-holder').val()


    $('#serial_qty_textarea_' + product_id).val($('.product-serial-textarea').val())
    $('#serial_qty_input_' + product_id).val($('.total-serial-count').text()).keyup()

    let quantity = Number($('.total-serial-count').text() | 0)

    let productCost = Number($('#product_costs_' + product_id).val() | 0)

    $('#product_subtotals_' + product_id).val((quantity * productCost).toFixed(2))


    $('#add_product_serial_modal').modal('hide')

    setItemSerial()
}


function countProductSerial(object)
{
    let serials = $(object).val()

    if (serials == '') {
        $('.total-serial-count').text(0)
    } else {
       let serialArray = serials.split(",");
       $('.total-serial-count').text(serialArray.length)
    }

}

function setItemSerial() {
    let total = 0
    $('.item-serial').each(function (index) {
        $(this).text(index+1)
        total += parseFloat($(this).closest('tr').find('.product-row-subtotal').val())
    })
    $('#subtotal').val(total.toFixed(2))
    calculateInvoiceAmount()
}

function removeRow(object) {
    let productId = $(object).closest('tr').find('.product-id').val()
    $('.select-product option[value=' + productId + ']').prop("disabled", false);
    $('.select-product').val('');
    $('.select-product').select2('focus');
    $('.select-product').select2();
    $(object).closest('tr').remove()
    setItemSerial()
}

$('.select-supplier').change(function () {
    let supplier_id = $(this).val()
    if (supplier_id != '') {
        let baseUrl = "/get-supplier-balance/" + supplier_id
        $.ajax({
            url: baseUrl,
            type: 'GET',
            success: function (res) {
                $('#previous_due').val(res.previous_due)
                $('#advance').val(res.advanced_payment)
            }
        })
    } else {
        $('#previous_due').val(0)
        $('#advance').val(0)
    }
})


$('.select-account').change(function () {
    if ($('.select-account').val() == '') {
        $('.account-balance').text('')
    } else {
        $('.account-balance').text($('.select-account option:selected').data('total-amount'))
    }
})


// prevent given amount is not greater than account balance
$('.given-amount').keyup(function () {
    let amount = $(this).val()
    let accountBalance = $('.account-balance').text()
    if (amount > 0 && accountBalance == '') {
        alert('Please select an account first')
        $(this).val(0)
    } else if (parseFloat(amount) > parseFloat(accountBalance)) {
        alert('Payment amount cant be up to ' + $('.select-account option:selected').text() +  ' account balance')
        $(this).val(0)
    }
})

function changeQuantity(object) {
    let quantity = $(object).val()
    if (quantity == '') {
        $(object).closest('tr').find('.product-row-subtotal').val(0)
    } else {
        let productCost = $(object).closest('tr').find('.product-cost').val()
        $(object).closest('tr').find('.product-row-subtotal').val((parseFloat(quantity) * parseFloat(productCost)).toFixed(2))
    }
    setItemSerial()
}

$('.grand-total-calculate').keyup(function () {
    calculateInvoiceAmount()
})
function calculateInvoiceAmount() {
    let itemTotalAmount = $('#subtotal').val()
    let invoiceDiscount = $('#invoice_discount').val()
    let invoiceTax = $('#invoice_tax').val()
    // let advance = $('#advance').val()
    // let previousDue = $('#previous_due').val()
    // let givenAmount = $('#total_paid').val()

    if (itemTotalAmount == '') {
        itemTotalAmount = 0;
    }
    if (invoiceDiscount == '') {
        invoiceDiscount = 0;
    }
    if (invoiceTax == '') {
        invoiceTax = 0;
    }

    let totalPayable = $('#total_payable_temp').val((parseFloat(itemTotalAmount) + parseFloat(invoiceTax) - parseFloat(invoiceDiscount)).toFixed(2))
}


function loadDatePicker()
{
    $('.expire-date').datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayHighlight: true,
    });
}
$('.save-btn').click(function () {
    let isSubmit = true
    if ($('.select-supplier').val() == '') {
        alert('Please Select Supplier')
        isSubmit = false
    }
    if ($('.item-serial').length == 0 && isSubmit == true) {
        isSubmit = false
        alert('Please purchase at least one product')
    }

    if ($('.given-amount').val() >= 0 && isSubmit == true) {
        if ($('.account-info').val() == '') {
            isSubmit = false
            alert('Please Select account')
        } else if (parseFloat($('.account-balance').text()) < parseFloat($('.given-amount').val())) {
            alert('Do not have available balance for this account' + parseFloat($('.account-balance').val()) + ' ' + parseFloat($('.given-amount').val()))
            isSubmit = false
        }
    }
    if (isSubmit == true) {
        $('#purchaseForm').submit()
    }
})
