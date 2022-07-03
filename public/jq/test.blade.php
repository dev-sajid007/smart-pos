<script>
        select2Loads({
            selector: '#supplier',
            url: '/people/suppliers',
        })

        $('#supplier').on('select2:select', function (e) {
            getSupplierBalance(e.target.value);
            get_supplier_product(e.target.value);
        });
    </script>

    <script type="text/javascript">

        let getSupplierBalance = (id) => {
            if (id != undefined) {
                $.getJSON(
                    '/get-current-balance?type=supplier&id=' + id,
                    function (balance) {
                        if (balance <= 0) {
                            $('.advance-group').show();
                            $('#advance').val(Math.abs(balance))
                            $('.prevDue-group').hide()
                        } else {

                            $('.prevDue-group').show();
                            $('#previous_due').val(balance)
                            $('.advance-group').hide();
                        }
                    }
                )
            }

        }

        function get_supplier_product(id) {

            $.ajax({
                url: "{{ route('purchases.get-supplier-product') }}",
                type: "get",
                data: {id: id},
                success: function (response) {
                    // alert(response);
                    $('#supplier_product').html(response);

                    $('#total_payable_temp').val(0)
                    getSupplierBalance(id);
                },
                error: function (xhr, status) {
                    alert('There is some error.Try after some time.');
                }
            });

            get_sub_total()
        }


        function get_sub_total(qty, n, elem) {

            qty = fixOnNull(qty, elem);

            $("#product_cost_" + n).prop('readonly', true);

            var product_price = parseFloat($('#product_cost_' + n).val());
            $("#sub_total_" + n).val(qty * product_price);

            var inv_sub_total = 0;
            for (var i = 0; i <= n; i++) {
                inv_sub_total += parseFloat($('#sub_total_' + i).val());
            }

            inv_sub_total = isNaN(inv_sub_total) ? 0 : inv_sub_total
            $("#invoice_sub_total").val(inv_sub_total);

            // let  inv_sub_total_temp =
            //     inv_sub_total + parseFloat($('#previous_due').val()) + parseFloat($('#advance').val());
            // $("#total_payable_temp").val(inv_sub_total_temp);
            showExactPayable(inv_sub_total);
            $("#total_payable").val(inv_sub_total);
            $('#total_due').val(inv_sub_total);
        }

        let showExactPayable = (amount) => {
            if (!amount) amount = 0;
            amount += parseFloat($('#previous_due').val()) - parseFloat($('#advance').val());
            amount = amount < 0 ? 0 : amount
            $("#total_payable_temp").val(amount);
        }


        function deduct_discount(amount, elem) {

            let discount_amount = fixOnNull(amount, elem);

            var tax_amount = fixOnNull($('#invoice_tax').val(), '#invoice_tax');

            let invoice_sub_total = $('#total_payable').val();
            let discount_deducted = (invoice_sub_total - discount_amount) + parseFloat(tax_amount);

            $('#total_payable_temp').val(discount_deducted);

            showExactPayable(discount_deducted);

            // $('#total_due').val(discount_deducted);
        }

        function add_tax(amount, elem) {

            let tax_amount = fixOnNull(amount, elem);

            $('#invoice_discount').prop('readonly', true);

            let discount_amount = $('#invoice_discount').val();

            let invoice_sub_total = $('#total_payable').val();

            let tax_added = (parseFloat(invoice_sub_total) + parseFloat(tax_amount)) - parseFloat(discount_amount);

            // $('#total_payable').val(tax_added);
            $('#total_payable_temp').val(tax_added);
            showExactPayable(tax_added)
            // $('#total_due').val(tax_added);
        }


        function fixOnNull(number, element) {
            if (!parseInt(number)) {
                $(element).val(0)
                return 0;
            }
            return number;
        }


        function totalAmount() {
            var total = 0;
            $('.service-prices').each(function (i, price) {
                var p = $(price).val();
                total += p ? parseFloat(p) : 0;
            });
            var subtotal = $('#subTotal').val(total);
            discountAmount();
        }




