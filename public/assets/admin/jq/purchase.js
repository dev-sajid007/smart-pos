let count = 0
select2Loads({
        selector: '#supplier',
        url: '/people/suppliers',
    })

    $('#supplier').on('select2:select', function (e) {
        getSupplierBalance(e.target.value);
        get_supplier_product(e.target.value);
    });


    let getSupplierBalance = (supplier_id) => {
        if (supplier_id != undefined) {
            let baseUrl = "/get-supplier-balance/" + supplier_id
            $.ajax({
                url: baseUrl,
                type: 'GET',
                success: function (res) {
                    $('#previous_due').val(res.previous_due)
                    $('#advance').val(res.advanced_payment)
                }
            })
        }
    }

    function get_supplier_product(id) {

        if (id != undefined) {
            $.get(
                `/get-supplier-product/${id}`,
                function (response) {
                    $('.add-new-product-button').css('visibility', 'visible')
                    $('.new_supplier_id').val(id)
                    $('#total_payable_temp').val(0)
                    getSupplierBalance(id);

                    if (response.length > 0) {
                        $('#supplier_product').html(response);

                    } else {
                        $('#supplier_product').html([])
                    }
                }
            )
        }
    }


    $(document).on('focus keyup', '.unit-cost, .quantity, #invoice_discount, #invoice_tax',
        function (e) {
            row(e).find('.totalLinePrice').val(totalLinePrice(e).toFixed(2))
            validateAmounts()
            $('#subtotal').val(subTotal(e));
        });

    function totalLinePrice(e) {
        let unitCost = parseFloat(row(e).find('.unit-cost').val());
        let quantity = parseFloat(row(e).find('.quantity').val());
        if (!unitCost) unitCost = 0;
        if (!quantity) quantity = 0;

        return unitCost * quantity;
    }

    function subTotal(e) {
        let linePriceRows = $('.totalLinePrice');

        let amount = 0;
        $.each(linePriceRows, function (i, row) {
            let lineTotal = parseFloat($(row).val());
            if (!lineTotal) lineTotal = 0;
            amount += lineTotal;
        });

        // discount(amount)
        let amountAfterDiscount = amountManipulation({
            getTargetSelector: '#invoice_discount',
            putTargetSelector: '#total_payable_temp',
            subTotal: amount,
            type: 'sub',
            forcedZero: true
        })

        //Tax
        let amountAfterTax = amountManipulation({
            getTargetSelector: '#invoice_tax',
            putTargetSelector: '#total_payable_temp',
            subTotal: amountAfterDiscount,
            type: 'add',
            forcedZero: true
        })

        //Prev Due
        let amountAfterPrevDue = amountManipulation({
            getTargetSelector: '#previous_due',
            putTargetSelector: '#total_payable_temp',
            subTotal: amountAfterTax,
            type: 'add',
            forcedZero: false
        })

        // //Advanced
        let advanced = amountManipulation({
            getTargetSelector: '#advance',
            putTargetSelector: '#total_payable_temp',
            subTotal: amountAfterPrevDue,
            type: 'sub',
            forcedZero: false
        })

        if (advanced < 0) {
            $('#total_payable_temp').val(0)
        }

        $('#total_payable_temp').val(parseFloat($('#total_payable_temp').val()).toFixed(2))
        return amount.toFixed(2);
    }

    function amountManipulation(settings) {
        let amount = parseFloat($(settings.getTargetSelector).val())

        switch (settings.type) {
            case 'sub': {
                $(settings.putTargetSelector).val(settings.subTotal - amount);
                return settings.subTotal - amount;
            }
            case 'add': {
                $(settings.putTargetSelector).val(settings.subTotal + amount);
                return settings.subTotal + amount;
            }
        }

    }

    function validateAmounts() {
        let totalPayable = parseFloat($('#total_payable_temp').val());
        let discount = parseFloat($('#invoice_discount').val());

        // if (discount > totalPayable) {
        //     $('#invoice_discount').val(0)
        //     alert('You cant add discount more than Payable Amount');
        // }
    }


    function row(e) {
        return $(e.target).parents('tr')
    }



    // Purchase Create Section
    function resetForm()
    {
        $('.productAddForm').trigger('reset');
    }

    function openCreateModal(e) {
        select2Loads({
            selector: '#fk_category_id',
            url: '/settings/categories'
        })

        select2Loads({
            selector: '#fk_product_unit_id',
            url: '/settings/product_units'
        })
    }

    $('.productAddForm').submit(function (e) {
        e.preventDefault();

        let that = $(this)
        triggerPreloader(true)
        window.axios.post("/products", that.serialize())
            .then(({data}) => {
                triggerPreloader(false)
                $('#addNew').modal('hide')
                $('#supplier_product').prepend(htmlString(data))

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
    })

    function triggerPreloader(isLoading = false) {
        if (isLoading) {
            $('.loading').css('display', 'flex')
        } else {
            $('.loading').css('display', 'none')
        }
    }



    function htmlString({product}){
        return ( `
            <tr>
                <td style="display: none">
                <input type="text" readonly tabindex="-1" name="product_ids[]"
                       class="form-control item-box product-id"
                       value="${product.id}">
                </td>
                <td>

                    <textarea name="product_name[]" tabindex="-1"
                    class="form-control item-box product-name" readonly>${product.product_name}</textarea>
                </td>
                <td>
                    <input type="text"  name="unit_cost[]"  min="1"
                           value="${product.product_cost}" class="form-control unit-cost item-box">
                </td>
                <td style="display: none">
                    <input type="text" tabindex="-1" name="unit_prices[]" readonly min="1"
                           value="${product.product_price}"
                           class="dynamic_product_price form-control item-box unit-prices" onblur="">
                </td>
                <td>
                    <input type="number" min="0" step="1"  name="quantities[]" value="0"
                           class="form-control changesNo qty_unit item-box quantity" autocomplete="off" placeholder="Qty"
                           onkeyup="">
                </td>
                <td>
                    <input type="number" min="0" step="1"  name="free_quantities[]" value="0"
                           class="form-control changesNo  free_qty_unit item-box free-quantity" autocomplete="off"
                           placeholder="Qty">
                </td>
                <td>
                    <input type="number" tabindex="-1" name="product_sub_total[]"  readonly="readonly"
                           min="0" class="form-control totalLinePrice item-box text-right" value="0" autocomplete="off"
                           placeholder="Sub
                           Total">
                </td>
            </tr>
        `)
    }




