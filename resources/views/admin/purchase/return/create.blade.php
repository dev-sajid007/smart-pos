@extends('admin.master')

@push('style')
    <style>
        .add-new-btn {border-radius: 0 0 0 0;}
        .add-new-product-button {padding: 4.5px;border-radius: 0px 5px 5px 0px; visibility: hidden;}
        .new-customer-btn {padding: 4.5px;border-radius: 0px 5px 5px 0px;margin-top: 28px;}
        .loading {display: none;justify-content: center;align-items: center; width: 100%;position: absolute;height: 100%; z-index: 11111;background: #ffffffc7;}
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

                <form class="form-horizontal" method="POST" action="{{ route('purchase-returns.store') }}">
                    @csrf
                    <div class="tile">
                        <a href="{{ route('purchase-returns.index') }}" class="btn btn-primary pull-right" style="float:
                         right;"><i class="fa fa-eye"></i> View Returns </a>
                        <h3 class="tile-title"> Purchase Return</h3>
                        <hr>

                        <div class="tile-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table" style="margin-right: 5% !important; margin-left: 1%">
                                        <tr>
                                            <th style="padding: 0 !important; border: none">Supplier</th>
                                            <th width="2%" style="border: none"></th>
                                            <th style="padding: 0 !important; border: none" width="30%">Reference No</th>
                                            <th width="10%" style="border: none"></th>
                                            <th style="padding: 0 !important; border: none" width="250px">Date</th>
                                        </tr>

                                        <tr>
                                            <td style="padding: 0 !important; border: none">
                                                <div class="form-group">
                                                    <select name="supplier_id" class="form-control select2" id="supplier_id" onchange="loadSupplierPurchaseInvoices(this)"> 
                                                        <option class="text-center" value="">-Select-</option>
                                                        @foreach($suppliers as $key => $supplier)
                                                            <option value="{{ $supplier->id }}" data-balance="{{ $supplier->balance }}" {{ $supplier->id == old('supplier_id') ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td style="border: none"></td>
                                            <td style="padding: 0 !important; border: none">
                                                <input name="reference" value="{{ old('reference') }}" class="form-control" type="text" placeholder="Reference">
                                                <div class="text-danger">
                                                    {{ $errors->has('reference') ? $errors->first('reference') : '' }}
                                                </div>
                                            </td>
                                            <td style="border: none"></td>
                                            <td style="padding: 0 !important; border: none">
                                                <input name="date" value="{{ old('date', date('Y-m-d')) }}" class="form-control dateField" type="text" placeholder="Date">
                                                <div class="text-danger">
                                                    {{ $errors->has('date') ? $errors->first('date') : '' }}
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>


                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-md-4 pr-0">
                                        <div class="form-group">
                                            <label class="control-label">Invoice</label>
                                            <select class="form-control select2" onchange="loadInvoiceProducts(this)" id="invoice_id"> 
                                                <option class="text-center" value="">-All-</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-md-4 pr-0">
                                        <div class="form-group">
                                            <label class="control-label">Product</label>
                                            <select class="form-control select2" onchange="selectProduct(this)" id="product_id"> 
                                                <option class="text-center" value="">-All-</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 pr-0">
                                        <div class="form-group">
                                            <label class="control-label">Available Qty</label>
                                            <input class="returnable-quantity form-control" type="text" value="" readonly>
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
                                                <input type="text" class="form-control supplier-previous-due text-right" value="0" readonly name="previous_due">
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
                </form>
            </div>
        </div>
    </main>
@endsection

@section('footer-script')
    <script src="{{ asset('jq/select2Loads.js') }}"></script>
    <script src="{{ asset('assets/admin/jq/purchase_return.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/jq/purchase.js') }}"></script>
    <script>
        

        function loadSupplierPurchaseInvoices(object)
        {
            $('.product-return-tbody').empty()
            $('.product-return-tbody').append('<tr class="not-found-row"><td class="text-danger text-center py-3" colspan="20">No records added!</td></tr>')

            $('.total-amount').val(0)
            $('.supplier-previous-due').val(0)
            $('.return-amount').val(0)
            $('.grand-total-amount').val(0)
            $('.current-balance').val(0)

            if($(object).val() != '') {
                $('.supplier-previous-due').val($('#supplier_id option:selected').data('balance'))
            }


            let invoice = $('#invoice_id')
            invoice.empty()
            invoice.append('<option value="">-All-</option>')

            $.get("/get-supplier-invoices", { supplier_id : $(object).val() },  function(data, status){
                $(data).each(function(index, item) {
                    invoice.append('<option value="' + item.id + '">' + item.invoice_no + '</option>')
                })
            })
            $('#invoice_id').select2()
            loadInvoiceProducts(object)
        }



        function loadInvoiceProducts(object)
        {
            let invoice_id = $('#invoice_id').val()
            let supplier_id = $('#supplier_id').val()
         
            let products = $('#product_id')
            products.empty()
            products.append('<option value="">-All-</option>')

            $.get("/get-supplier-purchased-products", { supplier_id : supplier_id, invoice_id : invoice_id },  function(data, status){
                $(data).each(function(index, item) {
                    products.append('<option data-product_stock="'+item.product_stock.available_quantity+'" data-quantity="' + item.quantity + '" data-price="' + item.price + '" value="' + item.id + '">' + item.name + ' (' + item.code + ')</option>')
                })
            })
            $('#product_id').select2()
        }


        function selectProduct(obejct) 
        {
            let productCost = $(obejct).find('option:selected').data('price')
            let quantity = $(obejct).find('option:selected').data('quantity')
            if(quantity==null){
                quantity =  $(obejct).find('option:selected').data('product_stock')
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


    
        function checkValidQty(object)
        {
            let returnable_qty = Number($('.returnable-quantity').val() | 0)
            let return_qty = Number($(object).val() | 0)
        
            if (return_qty > returnable_qty) {
                alert('You can not return more than ' + returnable_qty + ' quantity')
                $(object).val(returnable_qty)
            }
        }


        function productPriceEnterKeyEvent(evnt) 
        {
            let keycode = (evnt.keyCode ? evnt.keyCode : evnt.which);
            if(keycode == '13') {
                insertNewItem()
            }
            return keycode >= 46 && keycode <= 57
        }


        // add new item row when click on add more button
        function insertNewItem() {

            let tableTobody = $('.product-return-tbody')

            let quantity    = $('.select-quantity').val()
            let product     = $('#product_id option:selected')
            let price       = $('.select-unit-cost').val()
            let return_qty  = $('.returnable-quantity').val()


            let tr =    `<tr>
                            <td class="item-serial text-center"></td>
                            <td>
                                <input type="hidden" name="product_ids[]" value="${ product.val() }">
                                ${ product.text() }
                            </td>
                            <td>
                                <select name="condition_type[]" class="form-control select2 condition">
                                    <option value="1" selected>Good</option>
                                    <option value="0">Damaged</option>
                                </select>
                            </td>
                            <td class="text-center returnable-qty">${ product.data('product_stock') | return_qty }</td> 
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

            function calculateTotal()
            {
                let totalAmount     = Number($('.total-amount').val() | 0)
                let previousDue     = Number($('.supplier-previous-due').val() | 0)
                let grandTotal      = previousDue - totalAmount 
                let returnAmount    = Number($('.return-amount').val() | 0)

                $('.grand-total-amount').val(grandTotal)
                $('.current-balance').val(grandTotal + returnAmount)
            }
    
    </script>
@endsection
