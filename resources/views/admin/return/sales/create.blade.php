@extends('admin.master')
@push('style')
    <style>
        .add-new-btn {
            border-radius: 0 0 0 0;
        }

        .add-new-product-button {
            padding: 4.5px;
            border-radius: 0px 5px 5px 0px;
            visibility: hidden;
        }

        .new-customer-btn {
            padding: 4.5px;
            border-radius: 0px 5px 5px 0px;
            margin-top: 28px;
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
        td {
            border: 1px solid black;
        }
    </style>
@endpush

@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                @if($errors->any())
                    <div class="alert-danger">
                        <ul class="list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="form-horizontal" method="POST" action="{{ route('sale-returns.store') }}">
                    @csrf

                    <div class="tile">
                        <a href="{{ route('sale-returns.index') }}" class="btn btn-primary pull-right" style="float: right;">
                            <i class="fa fa-eye"></i> View Returns
                        </a>
                        <h3 class="tile-title">Sale Return</h3>
                        <hr>
                        <div class="tile-body">
                            <div class="row">

                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-md-4 pr-0">
                                            <div class="form-group">
                                                <label class="control-label">Customer</label>
                                                <select name="customer_id" class="form-control select2" id="customer-id" onchange="loadCustomerSaleInvoices(this)">
                                                    <option class="text-center" value="">-Select-</option>
                                                    @foreach($customers as $key => $customer)
                                                        <option value="{{ $customer->id }}" data-balance="{{ $customer->balance }}" {{ $customer->id == old('customer_id') ? 'selected' : '' }}>{{ $customer->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Reference No</label>
                                                <input name="reference" value="{{ old('reference') }}"
                                                    class="form-control" type="text" placeholder="Reference">
                                                <div class="text-danger">
                                                    {{ $errors->has('reference') ? $errors->first('reference') : '' }}
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-3">
                                            <div class="form-group ">
                                                <label class="control-label">Date</label>
                                                <input name="date" value="{{ old('date') ?? date('Y-m-d') }}"
                                                    class="form-control dateField" type="text" placeholder="Date">
                                                <div class="text-danger">
                                                    {{ $errors->has('date') ? $errors->first('date') : '' }}
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-md-4 pr-0">
                                            <div class="form-group">
                                                <label class="control-label">Invoice</label>
                                                <select class="form-control select2" onchange="loadInvoiceProducts(this)" id="invoice-id">
                                                    <option class="text-center" value="">-All-</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 pr-0">
                                            <div class="form-group">
                                                <label class="control-label">Product Serial</label>
                                                <input type="text" class="form-control" onkeypress="getInvoiceId()" onkeyup="getInvoiceId()" id="serial_id">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-md-4 pr-0">
                                            <div class="form-group">
                                                <label class="control-label">Product</label>
                                                <select class="form-control select2" onchange="selectProduct(this)" id="product-id">
                                                    <option class="text-center" value="">-All-</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2 pr-0">
                                            <div class="form-group">
                                                <label class="control-label">Returnable Qty</label>
                                                <input class="returnable-quantity form-control" type="text" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-2 pr-0">
                                            <div class="form-group">
                                                <label class="control-label">Quantity</label>
                                                <input class="select-quantity form-control" onkeyup="checkValidQty(this)" onkeypress="return selectProductQuantity(event)" type="text" value="">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 pr-0">
                                            <div class="form-group">
                                                <label class="control-label">Price</label>
                                                <input class="select-unit-cost form-control" onkeypress="return productPriceEnterKeyEvent(event)" type="text" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <table class="table table-sm table-bordered" style="border: none">
                                        <thead>
                                            <tr>
                                                <th width="3%" class="text-center">Sl.</th>
                                                <th width="28%">Product Name</th>
                                                <th>Condition</th>
                                                <th width="11%" class="text-center">Returnable Qty</th>
                                                <th width="11%" class="text-center">Return Qty</th>
                                                <th width="11%" class="text-right pr-2">Price</th>
                                                <th width="11%" class="text-right">Amount</th>
                                                <th width="1%"></th>
                                            </tr>
                                        </thead>

                                        <tbody class="product-return-tbody">
                                            <tr class="not-found-row">
                                                <td class="text-danger text-center py-3" colspan="20">No records added!</td>
                                            </tr>
                                        </tbody>


                                        <tfoot>
                                            <tr>
                                                <th colspan="5" rowspan="2" style="border: none">
                                                    <textarea name="comment" class="form-control" placeholder="Comments" style="height: 70px;"></textarea>
                                                </th>
                                                <th style="border: none" class="text-right">Total Amount :</th>
                                                <th style="border: none">
                                                    <input type="text" class="form-control total-amount text-right" value="0" readonly name="total_amount">
                                                </th>
                                            </tr>
                                            <tr>
                                                <th style="border: none" class="text-right">Previous Due :</th>
                                                <th style="border: none">
                                                    <input type="text" class="form-control customer-previous-due text-right" value="0" readonly name="previous_due">
                                                </th>
                                            </tr>
                                            <tr>
                                                <th style="border: none" colspan="6"  class="text-right">Grand Total :</th>
                                                <th style="border: none">
                                                    <input type="text" class="form-control grand-total-amount text-right" value="0" readonly name="grand_total_amount">
                                                </th>
                                            </tr>
                                            <tr>
                                                <th style="border: none" colspan="6" class="text-right">Return Amount :</th>
                                                <th style="border: none">
                                                    <input type="text" onkeyup="calculateTotal()" autocomplete="off" onkeypress="return event.charCode >= 46 && event.charCode <= 57" class="form-control return-amount text-right" value="0" name="return_amount">
                                                </th>
                                            </tr>
                                            <tr>
                                                <th style="border: none" colspan="6" class="text-right">Current Balance :</th>
                                                <th style="border: none">
                                                    <input type="text" class="form-control current-balance text-right" value="0" readonly name="current_balance">
                                                </th>
                                            </tr>
                                            <tr>
                                                <th style="border: none" colspan="6"></th>
                                                <th style="border: none" colspan="2">
                                                    <button class="btn btn-primary pull-right" type="submit" type="submit">
                                                        <i class="fa fa-fw fa-lg fa-check-circle"></i> Add Return
                                                    </button>
                                                </th>
                                            </tr>
                                        </tfoot>

                                    </table>
                                    <br>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

@endsection

@section('footer-script')
    <script src="{{ asset('jq/select2Loads.js') }}"></script>
    <script src="{{ asset('assets/admin/jq/sale_return.js') }}"></script>
    <script>

        function calculateTotal()
        {
            let totalAmount     = Number($('.total-amount').val() | 0)
            let previousDue     = Number($('.customer-previous-due').val() | 0)
            let grandTotal      = previousDue - totalAmount
            let returnAmount    = Number($('.return-amount').val() | 0)

            $('.grand-total-amount').val(grandTotal)
            $('.current-balance').val(grandTotal + returnAmount)
        }

        function loadCustomerSaleInvoices(object)
        {
            $('.product-return-tbody').empty()
            $('.product-return-tbody').append('<tr class="not-found-row"><td class="text-danger text-center py-3" colspan="20">No records added!</td></tr>')

            $('.total-amount').val(0)
            $('.customer-previous-due').val(0)
            $('.return-amount').val(0)
            $('.grand-total-amount').val(0)
            $('.current-balance').val(0)

            if($(object).val() != '') {
                $('.customer-previous-due').val($('#customer-id option:selected').data('balance'))
            }


            let invoice = $('#invoice-id')
            invoice.empty()
            invoice.append('<option value="">-All-</option>')

            $.get("/get-customer-invoices", { customer_id : $(object).val() },  function(data, status){
                $(data).each(function(index, item) {
                    invoice.append('<option value="' + item.id + '">' + item.invoice_no + '</option>')
                })
            })
            $('#invoice-id').select2()
            loadInvoiceProducts(object)
        }

        function loadInvoiceProducts(object)
        {
            let invoice_id = $('#invoice-id').val()
            let customer_id = $('#customer-id').val()

            let products = $('#product-id')
            products.empty()
            products.append('<option value="">-All-</option>')

            $.get("/get-customer-buying-products", { customer_id : customer_id, invoice_id : invoice_id },  function(data, status){
                $(data).each(function(index, item) {
                    products.append('<option data-product_stock="'+item.product_stock+'" data-quantity="' + item.quantity + '" data-price="' + item.price + '" data-warrany="' + item.warranty_left + '" data-guarrantee="' + item.guarantee_left + '" value="' + item.id + '">' + item.name + ' (' + item.code + ')</option>')
                })
            })
            $('#product-id').select2()
        }


        function getInvoiceId(){
            let serial_id = $('#serial_id').val()
            $.get("/get-invoice-id", { serial_id : serial_id },  function(dataz, status){
                if(dataz != null){
                    $('#product-id').empty()
                    $('#invoice-id').empty()
                    $('#customer-id').empty()
                    loadProductsBySerial(dataz)
                }
            });
        }
        function loadProductsBySerial(dataz){
            let saleId      = dataz.saleId
            let invoice_id  = dataz.invoice_no
            let customer_id = dataz.customer_id
            let products    = $('#product-id')
            let InvoiceId   = $('#invoice-id')
            let customer    = $('#customer-id')

            customer.empty()
            $.get("/get-customer-data", { customer_id : customer_id },  function(customerdata, status){
                $(customerdata).each(function(index, item) {
                    // console.log(item);
                    customer.append('<option value="'+item.id+'" data-balance="'+item.current_balance+'" selected">'+item.name+'</option>');
                })
            })

            InvoiceId.empty()
            InvoiceId.append('<option class="text-center" value="'+saleId+'" selected>'+invoice_id+'</option>')

            products.empty()
            products.append('<option value="">-All-</option>')

            $.get("/get-customer-buying-products", { customer_id : customer_id, invoice_id : saleId },  function(productsdata, status){
                $(productsdata).each(function(index, item) {
                    products.append('<option data-product_stock="'+item.product_stock+'" data-quantity="' + item.quantity + '" data-price="' + item.price + '" data-warrany="' + item.warranty_left + '" data-guarrantee="' + item.guarantee_left + '" value="' + item.id + '">' + item.name + ' (' + item.code + ')</option>')
                })
            })
            $('#product-id').select2()
        }


        function selectProduct(obejct)
        {
            let productCost = $(obejct).find('option:selected').data('price')
            let quantity = $(obejct).find('option:selected').data('quantity')
            
            if(quantity==null){
                quantity= $(obejct).find('option:selected').data('product_stock')
            }
            $('.select-unit-cost').val(productCost)
            $('.returnable-quantity').val(quantity)

            $('.select-product').select2();
            $('.select-quantity').focus()
        }

        function selectProductQuantity(evnt)
        {
            let keycode = (evnt.keyCode ? evnt.keyCode : evnt.which);
            if(keycode == '13') {
                evnt.preventDefault()
                $('.select-unit-cost').focus()
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

        function checkValidQty(object)
        {
            let returnable_qty = Number($('.returnable-quantity').val() | 0)
            let return_qty = Number($(object).val() | 0)

            // if (return_qty > returnable_qty) {
            //     alert('You can not return more than ' + returnable_qty + ' quantity')
            //     $(object).val(returnable_qty)
            // }
        }


        // add new item row when click on add more button
        function insertNewItem() {

            let tableTobody = $('.product-return-tbody')

            let quantity    = $('.select-quantity').val()
            let product     = $('#product-id option:selected')
            let price       = $('.select-unit-cost').val()
            let return_qty  = $('.returnable-quantity').val()


            let tr =    `<tr>
                            <td class="item-serial text-center"></td>
                            <td>
                                <input type="hidden" name="product_ids[]" value="${ product.val() }">
                                ${ product.text() }
                                <p>${ product.data('warrany') } ${ product.data('guarrantee')  }</p>
                            </td>
                            <td>
                                <select name="condition_type[]" class="form-control select2 condition">
                                    <option value="Good" selected>Good</option>
                                    <option value="Damage">Damage</option>
                                </select>
                            </td>
                            <td class="text-center returnable-qty">${ return_qty | 0 }</td>
                            <td class="text-center">
                                <input type="hidden" class="product-quantity" name="quantities[]" value="${ quantity }">
                                ${ quantity }
                            </td>
                            <td class="text-right">
                                <input type="hidden" class="product-price" name="prices[]" value="${ price }">
                                ${ price }
                            </td>
                            <td class="text-right subtotal">${ Number(quantity) * Number(price) }</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm" title="Remove This Row" onclick="removeRow(this)">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>`


                        tableTobody.append(tr)
                        $('.condition').select2();
                        setItemSerial()
            }

            function setItemSerial()
            {
                $('.not-found-row').remove()
                let totalAmount = 0

                $('.item-serial').each(function(index) {
                    $(this).text((index + 1))
                    totalAmount += Number($(this).closest('tr').find('.subtotal').text())
                })
                $('.total-amount').val(totalAmount)
                calculateTotal()
            }

            function removeRow(object) {
                $(object).closest('tr').remove();
                let totalAmount = 0
                $('.item-serial').each(function(index) {
                    totalAmount += Number($(this).closest('tr').find('.subtotal').text())
                })
                $('.total-amount').val(totalAmount)
            }

    </script>
@endsection


