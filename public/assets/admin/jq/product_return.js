

    function setCustomer({id, name}) {
        const selectedCustomer = new Option(name, id, true, true);
        $('#customer_id').append(selectedCustomer)
            .trigger('change');
    }


    function resetForm() {
        $('.productAddForm').trigger('reset');
    }

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
                            <select class="form-control product_name" name="product_id[]">
                            </select>
                        </div>
                        <button type="button"
                                onclick="openCreateModal(this)"
                                data-toggle="modal"
                                data-target="#addNew"
                                class="btn btn-sm btn-primary
                        add-new-btn">+</button>
                    </div>
                </td>
                <td>
                    <input type="text" name="available_qty" class="form-control available-quantity" value="0"
                           placeholder="Available Quantity" readonly>
                </td>
                <td>
                    <textarea name="description[]" class="form-control" placeholder="Description"></textarea>
                </td>
                <td>
                    <select name="type[]" style="height: 33px">
                        <option value="1">Good</option>
                        <option value="0">Damaged</option>
                    </select>
                </td>
                <td>
                    <input value="0" class="form-control" name="quantity[]" type="text" placeholder="Damaged Quantity"">
                </td>
                <td>
                    <input value="0" class="form-control subtotal" name="subtotal[]" type="text" placeholder="Amount">
                </td>
                <td>
                    <button type="button" onclick="deleteRow(this)" class="btn btn-danger btn-sm"><i class="fa
                    fa-trash"></i></button>
                </td>
            </tr>
            `;
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
                $(e.target).parents('.r-row').find('.available-quantity').val(data[0])
            })
        }
    })
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
        subtotal = !parseFloat($(subtotal).val()) ? 0 : parseFloat($(subtotal).val());
        amount += subtotal
    })

    $('.amount').val(amount)

}