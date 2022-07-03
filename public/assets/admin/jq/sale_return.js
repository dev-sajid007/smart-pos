function triggerSelect() {
    select2Loads({
        selector: '.product_name',
        url: '/saleable-products',
    })
}
triggerSelect()
function addRow() {
    const htmlString = `
        <tr class="r-row">
            <td>
                <div class="row">
                    <div class="col-sm-11 pr-0">
                        <select class="form-control product_name" name="product_id[]"></select>
                    </div>
                </div>
            </td>
            <td>
                <select class="item_type" onchange="changeItemType(this)" name="type[]" style="height: 33px">
                    <option value="1">Good</option>
                    <option value="0">Damaged</option>
                </select>
            </td>
            <td>
                <input type="text" name="available_qty" class="form-control available-quantity" value="0" placeholder="Available Quantity" readonly>
            </td>
            <td><input value="0" class="form-control return-quantity text-center" onkeyup="changeReturnQuantity(this)" onkeypress="return validationQuanitytInput(event.charCode, this)" name="quantity[]" type="text" placeholder="Return Quantity""></td>
            <td><input value="0" class="form-control unit-cost text-center" onkeyup="changeUnitCost(this)" name="unit_costs[]" type="text" placeholder="Unit Cost" onkeypress="return validationNumberInput(event.charCode)"></td>
            <td><input value="0" class="form-control subtotal text-right" name="subtotal[]" type="text" placeholder="Amount" readonly></td>
            <td><button type="button" onclick="deleteRow(this)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
        </tr>`;

    $('.r-group').append(htmlString);
    triggerSelect()
    setAvailableQuantity()
    calculateSubtotal()

}

function deleteRow(element) {
    $(element).parents('.r-row').remove()
    calculateSubtotal()
}

function setAvailableQuantity() {
    $('.product_name').change(function (e) {
        if (parseFloat(e.target.value)) {
            $.get(`/sales/available_quantity/${e.target.value}`, function (data) {

                if (data != '') {
                    $(e.target).parents('.r-row').find('.available-quantity').val(data.available_quantity)
                    $(e.target).parents('.r-row').find('.unit-cost').val(data.stock_product.product_price)

                    if($(e.target).parents('.r-row').find('.item_type').val() == 0) {
                        $(e.target).parents('.r-row').find('.available-quantity').val(data.wastage_quantity)
                        $(e.target).parents('.r-row').find('.available-quantity').focus()
                    }
                } else {
                    setAvailableQuantityFromProduct(e)
                }

            })
        }
    })
}

function setAvailableQuantityFromProduct(object) {


    $.get(`/sales/product-available-quantity/${object.target.value}`, function (data) {
        $(object.target).parents('.r-row').find('.available-quantity').val(data.opening_quantity)
        $(object.target).parents('.r-row').find('.unit-cost').val(data.product_cost)

        if($(object.target).parents('.r-row').find('.item_type').val() == 0) {
            $(object.target).parents('.r-row').find('.available-quantity').val(data.opening_quantity)
            $(object.target).parents('.r-row').find('.available-quantity').focus()
        }
    })

}

function changeItemType(event) {
    let product_id = $(event).closest('tr').find('.product_name').val()
    let item_type = $(event)

    if (product_id != '') {
        $.get(`/sales/available_quantity/${product_id}`, function (data) {
            item_type.closest('tr').find('.available-quantity').val(data.available_quantity)

            if (item_type.val() == 0) {
                item_type.closest('tr').find('.available-quantity').val(data.wastage_quantity)
            }
        })
    }
}

function changeReturnQuantity(event) {
    let quantity = $(event).val()

    $(event).closest('tr').find('.subtotal').val('')

    if (quantity != '') {
        let cost = $(event).closest('tr').find('.unit-cost').val()
        $(event).closest('tr').find('.subtotal').val(parseFloat(quantity) * parseFloat(cost))
        calculateSubtotal()
    }
}


function validationNumberInput(object) {
    if(object == 13) {
        addRow()
    }
    return (object >= 46 && object <= 57) || object == 13
}

function validationQuanitytInput(object, item) {
    if(object == 13) {
        $(item).closest('tr').find('.unit-cost').focus()
    }
    return (object >= 46 && object <= 57) || object == 13
}

function changeUnitCost(event) {
    let cost = $(event).val()

    $(event).closest('tr').find('.subtotal').val('')

    if (cost != '') {
        let quantity = $(event).closest('tr').find('.return-quantity').val()
        $(event).closest('tr').find('.subtotal').val((parseFloat(quantity) * parseFloat(cost)).toFixed(2))
    }
    calculateSubtotal()
}


setAvailableQuantity()


function checkQuantity(element, event) {
    if (parseFloat(event.keyCode) === 13) {
        addRow()
    }


    let available = parseFloat($(element).parents('.r-row').find('.available-quantity').val());
    let quantity = parseFloat($(element).val());
    if (!quantity) quantity = 0;
}
$(document).on('keypress', 'input', function (e) {
    return e.which !== 13;
});
let parentElement = {};
function openCreateModal(elem) {
    select2Loads({
        selector: '#fk_category_id',
        url: '/settings/categories'
    })
    select2Loads({
        selector: '#supplier_id',
        url: '/people/suppliers'
    })
    select2Loads({
        selector: '#fk_product_unit_id',
        url: '/settings/product_units'
    })
    parentElement = $(elem)
}
$('.productAddForm').submit(function (e) {
    e.preventDefault();

    let that = $(this)
    triggerPreloader(true)


    window.axios.post("/products", that.serialize())
        .then(({data}) => {
            triggerPreloader(false)
            $('#addNew').modal('hide')
            loadImmediateSelectBox(data.product)
        })
        .catch((error) => {
            triggerPreloader(false)
            const errors = error.response.data.errors
            $.each(errors, (i, error) => {
                $('.error-ul').append(` <li class="text-danger">${error[0]}</li> `)
                that.delegate(':input', 'focus', function () {
                    $('.error-ul li').remove();
                })
            })
        });
    resetForm()
})

function triggerPreloader(isLoading = false) {
    if (isLoading) {
        $('.loading').css('display', 'flex')
    } else {
        $('.loading').css('display', 'none')
    }
}

function loadImmediateSelectBox(product) {
    let newOption = new Option(product.product_name, product.id, true, true);

    parentElement.parents('.r-row')
        .find('.product_name')
        .append(newOption)
        .trigger('change');

    parentElement = {}
}

$(document).keyup('.subtotal', () => {
    calculateSubtotal()
})

function calculateSubtotal() {
    let subtotals = $('.subtotal')
    let amount = 0;

    $.each(subtotals, (i, subtotal) => {
        subtotals = !parseFloat($(subtotal).val()) ? 0 : parseFloat($(subtotal).val());
        if (subtotals != '') {
            amount += subtotals
        }
    })
    $('.amount').val(amount)

}
