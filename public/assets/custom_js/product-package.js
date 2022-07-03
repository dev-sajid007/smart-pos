


$('.select-product').change(function () {
    let productCost = $(this).find('option:selected').data('product-price')
    $('.select-unit-price').val(productCost)
    $('.select-quantity').val(1).focus()
    $('.select-product').select2()
})

$('.select-quantity').keypress(function(event) {
    let keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13') {
        $('.select-unit-price').focus()
        return false;
    }
});



function insertNewItem () {
    let selected_item   = $('.select-product').find('option:selected');
    let productId       = selected_item.val()
    let productName     = selected_item.data('product-name')
    let productCode     = selected_item.data('product-code')
    let productCost     = $('.select-unit-price').val()

    let quantity        = $('.select-quantity').val()

    if (productId != '' && quantity != '') {
        let tr =    '<tr>' +
                        '<td class="item-serial"></td>' +
                        '<td>' + productName + '<input type="hidden" name="product_ids[]" class="product-id" value="' + productId + '"></td>' +
                        '<td>' + productCode + '<input type="hidden" name="detail_ids[]" value=""></td>' +
                        '<td><input type="text" readonly name="quantities[]" class="product-quantity form-control text-center" value="' + quantity + '"></td>' +
                        '<td><input type="text" readonly name="unit_cost[]" class="product-price form-control text-center" value="' + productCost + '"></td>' +
                        '<td><input type="text" readonly name="product_sub_total[]" class="product-row-subtotal form-control text-center" value="' + (productCost * quantity) + '"></td>' +
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
    } else {
        alert('Select product and fill quantity')
    }
}

function setItemSerial() {
    let total = 0
    $('.item-serial').each(function (index) {
        $(this).text(index+1)
        total += parseFloat($(this).closest('tr').find('.product-row-subtotal').val())
    })
    $('.grand-total').text(total)
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



$('.save-btn').click(function () {
    let isSubmit = true

    if (isSubmit == true) {
        $('#purchaseForm').submit()
    }
})
