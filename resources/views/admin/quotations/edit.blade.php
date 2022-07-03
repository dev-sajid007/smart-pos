@extends('admin.master')
@section('content')

    <main class="app-content">
        <form class="form-horizontal" method="POST" action="{{ route('quotations.update', $quotation->id) }}">
            @csrf
            @method("PUT")
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
                                    Edit Quotation
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <input name="quotation_date" value="{{ date('Y-m-d') }}"
                                                   class="form-control" type="hidden">

                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <label for="" class="control-label">Select Customer</label>
                                                    <select name="fk_customer_id" class="form-control select2">
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}" {{ $customer->id == $quotation->fk_customer_id ? 'selected':'' }}>{{ $customer->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>

                                        </div>


                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-sm" id="product_table">
                                                <thead>
                                                <tr>
                                                    <th width="25%">Product Name</th>
                                                    <th width="20%">Price</th>
                                                    <th>Quantity</th>
                                                    {{--                                                          <th width="10%">Unit</th>--}}
                                                    <th>Sub Total</th>

                                                </tr>
                                                </thead>

                                                @php
                                                    $count_rows = count($quotation->quotation_details);
                                                @endphp
                                                @foreach($quotation->quotation_details as $key=>$details)
                                                    <input type="hidden" name="quotation_details_id[]"
                                                           value="{{ $details->id }}">
                                                    <tr>
                                                        <td>
                                                            <select name="product_ids[]" id="product_name_{{ $key  }}"
                                                                    class="dynamic_product form-control select2"
                                                                    onchange="show_product_price(this.value, {{ $key }})">
                                                                <option value="">select</option>
                                                                @foreach ($products as $product)
                                                                    <option value="{{ $product->id }}" {{ $product->id == $details->fk_product_id ? 'selected':'' }}>{{ $product->product_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="number" min="1" name="unit_prices[]"
                                                                   value="{{ $details->unit_price }}"
                                                                   id="product_price_{{ $key }}" readonly="readonly"
                                                                   class="dynamic_product_price form-control"></td>
                                                        <td>
                                                            <input type="number" min="1" step="1"
                                                                   id="quantity_{{ $key }}" name="quantities[]"
                                                                   value="{{ $details->quantity }}" id="quantities"
                                                                   class="form-control changesNo" autocomplete="off"
                                                                   placeholder="Qty"
                                                                   onkeyup="get_sub_total(this.value, {{ $key }}, {{ $count_rows }})">
                                                        </td>
                                                        {{--                                                              <td>--}}
                                                        {{--                                                                  <div id="product_unit_1"></div>--}}
                                                        {{--                                                              </td>--}}
                                                        <td><input type="number" name="product_sub_total[]"
                                                                   value="{{ $details->product_sub_total }}"
                                                                   id="sub_total_{{ $key }}" readonly="readonly" min="0"
                                                                   id="txtResult" class="form-control totalLinePrice"
                                                                   autocomplete="off" placeholder="Sub Total">
                                                        </td>


                                                    </tr>

                                                    @endforeach

                                                    </tbody>
                                            </table>
                                            {{--                                            <span class="btn btn-sm btn-primary pull-right addRow"><i class="fa fa-plus"></i> Add New</span>--}}

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
                                        <input type="number" class="form-control" name="sub_total"
                                               id="invoice_sub_total" readonly="readonly"
                                               value="{{ $quotation->sub_total }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="">Discount(Deduction)</label>
                                        <input type="number" class="form-control" name="invoice_discount"
                                               id="invoice_discount" onkeyup="deduct_discount(this.value)"
                                               value="{{ $quotation->invoice_discount }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="">Tax/Others (Addition)</label>
                                        <input type="number" class="form-control" name="invoice_tax" id="invoice_tax"
                                               onkeyup="add_tax(this.value)" value="{{ $quotation->invoice_tax }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="">Total Payable</label>
                                        <input type="number" class="form-control" name="total_payable"
                                               id="total_payable" readonly="readonly"
                                               value="{{ $quotation->total_payable }}">
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-8">
                                            <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-fw
                                            fa-lg
                                            fa-check-circle"></i> Update Quotation
                                            </button>
                                        </div>
                                        <div class="col-sm-4">
                                            <a href="{{ route('quotations.index') }}"
                                               class="btn btn-sm btn-danger btn-block">List</a>
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



    @include('admin.includes.date_field')
@endsection

@section('footer-script')
    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>


    <script type="text/javascript">
        $('.select2').select2();
    </script>

    <script>


        function show_product_price(product_id, n) {
            var product_id = product_id;
            $.ajax({
                url: "{{ url('products/get_product_by_id') }}?id=" + product_id,
                type: "GET",
                success: function (res) {
                    if (res) {
                        $.each(res, function (key, value) {
                            $('#product_price_' + n).val(value['product_price']);
                            $('#product_unit_' + n).html(value['product_unit']['name']);
                        });
                    }
                }
            });
        }

        function get_sub_total(qty, o, n) {
            // alert(qty+o+n);
            var o = o;
            var qty = parseInt(qty);
            var product_price = parseFloat($('#product_price_' + o).val());
            $("#sub_total_" + o).val(qty * product_price);
            var inv_sub_total = 0;
            for (var i = 0; i <= n - 1; i++) {
                inv_sub_total += parseFloat($('#sub_total_' + i).val());
            }
            $("#invoice_sub_total").val(inv_sub_total);
            $("#total_payable").val(inv_sub_total);
        }

        function deduct_discount(discount_amount) {
            var tax_amount = $('#invoice_tax').val();
            var discount_amount = discount_amount <= 0 ? 0 : parseFloat(discount_amount);
            var invoice_sub_total = $('#invoice_sub_total').val();
            var discount_deducted = (invoice_sub_total - discount_amount) + parseFloat(tax_amount);
            $('#total_payable').val(discount_deducted);
        }

        function add_tax(tax_amount) {
            var discount_amount = $('#invoice_discount').val();
            var tax_amount = tax_amount <= 0 ? 0 : tax_amount;
            var invoice_sub_total = $('#invoice_sub_total').val();
            var tax_added = (parseFloat(invoice_sub_total) + parseFloat(tax_amount)) - parseFloat(discount_amount);
            $('#total_payable').val(tax_added);
        }
    </script>
@stop