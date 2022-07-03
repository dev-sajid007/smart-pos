@extends('admin.master')
@section('content')




    <main class="app-content">
        <form class="form-horizontal" method="POST" action="{{ route('purchases.update', $purchase->id) }}">
            @csrf
            @method("PUT")

            <input name="purchase_date" value="{{ old('purchase_date') == '' ? date('Y-m-d'):old('purchase_date') }}" class="form-control" type="hidden" placeholder="Date" readonly="readonly">

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

                    <div class="row">
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    Add New Purchase
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group row">
                                                <div class="col-md-8">
                                                    <select name="fk_supplier_id" id="supplier" onchange="get_supplier_product()" class="select2" >
                                                        <option value="">- Select Supplier -</option>
                                                        @foreach ($suppliers as $supplier)
                                                            <option {{ $supplier->id == $purchase->fk_supplier_id ? 'selected':'' }} value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>

                                        </div>

                                        {{--                                              <div class="col-md-4 offset-4">--}}
                                        {{--                                                  <div class="form-group">--}}
                                        {{--                                                      <label for="" class="h5">Purchase Status</label>--}}
                                        {{--                                                      <select name="fk_status_id" id="" class="form-control">--}}
                                        {{--                                                          @foreach ($statuses as $status)--}}
                                        {{--                                                              <option {{ old('fk_status_id') == $status->id ? 'selected' : '' }} value="{{ $status->id }}">{{ $status->name }}</option>--}}
                                        {{--                                                          @endforeach--}}
                                        {{--                                                      </select>--}}
                                        {{--                                                  </div>--}}
                                        {{--                                              </div>--}}
                                        <input type="hidden" name="fk_status_id" value="1">

                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    {{--                                                          <th>Product ID</th>--}}
                                                    <th width="28%">Product Name</th>
                                                    <th>Unit Cost</th>
                                                    {{--                                                          <th>Price</th>--}}
                                                    <th>Quantity</th>
                                                    <th>Free Quantity</th>
                                                    <th>Sub Total</th>
                                                </tr>
                                                </thead>
                                                <tbody id="supplier_product">


                                                @php
                                                    $count_rows = count($purchase_details);
                                                @endphp
                                                @foreach($purchase_details as $key=>$details)
                                                    <input type="hidden" name="purchase_details_id[]" value="{{ $details->id }}">

                                                        <tr>
                                                            <td style="display: none">
                                                                <input type="text" tabindex="-1" readonly name="product_ids[]" id="product_id_{{ $key+1 }}" class="form-control" value="{{ $details->product->id }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" tabindex="-1" name="product_name[]" readonly id="product_name_{{ $key+1 }}" class="form-control" value="{{ $details->product->product_name }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" tabindex="-1" name="unit_cost[]" id="product_cost_{{ $key+1 }}" readonly min="1" value="{{ $details->product->product_cost }}" class="form-control" >
                                                            </td>
                                                            <td style="display: none">
                                                                <input type="number" tabindex="-1" name="unit_prices[]" readonly min="1" value="{{ $details->product->product_price }}" id="product_price" class="dynamic_product_price form-control" onblur="">
                                                            </td>
                                                            <td>
                                                                <input type="number" min="0" step="1"  value="{{ $details->quantity }}" id="quantity_{{ $key+1 }}" name="quantities[]" class="form-control changesNo  qty_unit" autocomplete="off" placeholder="Qty" onkeyup="get_sub_total(this.value, {{ $key+1 }})">
                                                            </td>

                                                            <td>
                                                                <input type="number" min="0" step="1"  value="{{ $details->free_quantity }}" id="free_quantity" name="free_quantities[]" class="form-control changesNo  free_qty_unit" autocomplete="off" placeholder="Qty" onkeyup="get_sub_total_free_qty(this.value, {{ $key+1 }})">
                                                            </td>

                                                            <td>
                                                                <input type="number" tabindex="-1" name="product_sub_total[]" value="{{ $details->product_sub_total }}"  id="sub_total_{{ $key+1 }}" readonly="readonly" min="0"  class="form-control totalLinePrice" autocomplete="off" placeholder="Sub Total">
                                                            </td>
                                                        </tr>

                                                    @endforeach



                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    Total
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="">Total</label>
                                        <input type="number" class="form-control" name="sub_total" id="invoice_sub_total" readonly="readonly" value="{{ $purchase->sub_total }}">

                                    </div>
                                    <div class="form-group">
                                        <label for="">Discount (Deduction)</label>
                                        <input type="number" class="form-control" name="invoice_discount" id="invoice_discount" value="{{ $purchase->invoice_discount }}" onkeyup="deduct_discount(this.value)">

                                    </div>
                                    <div class="form-group">
                                        <label for="">Tax/Others (Addition)</label>
                                        <input type="number" class="form-control" name="invoice_tax" id="invoice_tax" value="{{ $purchase->invoice_tax }}" onkeyup="add_tax(this.value)">

                                    </div>
                                    <div class="form-group">
                                        <label for="">Total Payable</label>
                                        <input type="number" class="form-control" name="total_payable" id="total_payable" value="{{ $purchase->total_payable }}" readonly="readonly">

                                    </div>
                                    @if($account_linked)
                                        <div class="form-group">
                                            <label for="">Paid Amount</label>
                                            <input type="number" name="total_paid_amount" id="total_paid" value="{{ $purchase->paid_amount }}" class="form-control" onkeyup="get_due_amount(this.value)">

                                        </div>
                                        <div class="form-group">
                                            <label for="">Due Amount</label>
                                            <input type="number" name="total_due" id="total_due" readonly="readonly" class="form-control" value="{{ $purchase->due_amount }}">

                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <button class="btn btn-sm btn-primary">Update and Approved</button>
                                        <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-danger">List</a>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="account_info">Account Information</label>
                                                <select name="account_information" id="account_info" class="select2">
                                                    @foreach($account_infos as $account_info)
                                                        <option {{ $account_info->default_account == 1 ? 'selected' : '' }} value="{{ $account_info->id }}">{{ $account_info->account_name .' , '. $account_info->account_no }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </form>
    </main>

    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <script  src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>

    <script type="text/javascript">

        function get_supplier_product(){
            var id=document.getElementById("supplier").value;
            // alert(id);

            $.ajax({
                url  : "{{ route('purchases.get-supplier-product') }}",
                type : "get",
                data : {id:id},
                success : function(response){
                    // alert(response);
                    $('#supplier_product').html(response);
                },
                error : function(xhr, status){
                    alert('There is some error.Try after some time.');
                }
            });
            get_sub_total()
        }

        $('.select2').select2();




        function get_sub_total(qty, n){
            $("#product_cost_"+n).prop('readonly', true);
            var qty = parseInt(qty);
            var product_price = parseFloat($('#product_cost_'+n).val());
            $("#sub_total_"+n).val(qty*product_price);

            var inv_sub_total = 0;
            for(var i = 1; i<=n; i++){
                inv_sub_total += parseFloat($('#sub_total_'+i).val());

            }
            $("#invoice_sub_total").val(inv_sub_total);
            $("#total_payable").val(inv_sub_total);
            $('#total_due').val(inv_sub_total);
        }



        function deduct_discount(discount_amount){
            var tax_amount = $('#invoice_tax').val();
            var discount_amount = discount_amount <= 0 ? 0:parseFloat(discount_amount);
            var invoice_sub_total = $('#invoice_sub_total').val();
            var discount_deducted = (invoice_sub_total-discount_amount)+parseFloat(tax_amount);
            $('#total_payable').val(discount_deducted);
            $('#total_due').val(discount_deducted);
        }

        function add_tax(tax_amount){
            $('#invoice_discount').prop('readonly', true);
            var discount_amount = $('#invoice_discount').val();
            var tax_amount = tax_amount <= 0 ? 0:tax_amount;
            var invoice_sub_total = $('#invoice_sub_total').val();
            var tax_added = (parseFloat(invoice_sub_total)+parseFloat(tax_amount))-parseFloat(discount_amount);
            $('#total_payable').val(tax_added);
            $('#total_due').val(tax_added);
        }

        function get_due_amount(paid_amount){
            $('#invoice_discount').prop('readonly', true);
            $('#invoice_tax').prop('readonly', true);
            var payable_amount = $('#total_payable').val();
            var paid_amount = parseFloat(paid_amount);
            if(parseFloat(payable_amount) > 0 && paid_amount <= payable_amount){
                due_amount = payable_amount-paid_amount;
                $('#total_due').val(due_amount);
            }else{
                $('#total_paid').val(payable_amount);
                $('#total_due').val(0);
                alert('Paid amount must be less or equal to payable amount');
            }
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


        $(document).ready(function(){


            $('form').on('focus', 'input[type=number]', function(e){
                $(this).on('mousewheel.disableScroll', function(e){
                    e.preventDefault()
                })
            });

            // Restore scroll on number inputs.
            $('form').on('blur', 'input[type=number]', function(e) {
                $(this).off('wheel');
            });

            // Disable up and down keys.
            $('form').on('keydown', 'input[type=number]', function(e) {
                if ( e.which == 38 || e.which == 40 )
                    e.preventDefault();
            });

        });




    </script>




    @include('admin.includes.date_field')
@endsection