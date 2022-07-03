@extends('admin.master')
@section('content')

    <main class="app-content">
        @include('admin.people.customers.quickadd', ['errors' => $errors])


        <form class="form-horizontal" method="POST" action="{{ route('quotations.store') }}">
            @csrf
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
                                    Add New Quotation
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <input name="quotation_date" value="{{ date('Y-m-d') }}"
                                                   class="form-control" type="hidden">

                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <label for="" class="control-label">Select Customer</label>
                                                    <select name="fk_customer_id" class="form-control customer_id">
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-sm btn-info" tabindex="-1" title="Add New Customer" data-toggle="modal" data-target="#addnew" style="margin-top: 33px;"><i class="fa fa-plus" ></i> Add New Customer</button>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>


                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered" id="product_table">
                                                <thead>
                                                <tr>
                                                    <th width="25%">Product Name</th>
                                                    <th width="20%">Price</th>
                                                    <th>Quantity</th>
                                                    {{--                                                          <th width="10%">Unit</th>--}}
                                                    <th>Sub Total</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>

                                                @if (old('product_ids') == true)

                                                    @foreach(old('product_ids') as $key => $value)

                                                        <tr>
                                                            <td>
                                                                <select name="product_ids[]" id="product_1"
                                                                        class="dynamic_product form-control select2"
                                                                        onchange="show_product_price(this.value, {{ $key+1 }})">
                                                                    <option value="0">Select</option>
                                                                    {{--@foreach ($products as $product)--}}
                                                                        {{--<option {{ old('product_ids')[$key]  == $product->id ? 'selected' : '' }} value="{{ $product->id }}">{{ $product->product_name }}</option>--}}
                                                                    {{--@endforeach--}}
                                                                </select>
                                                            </td>
                                                            <td><input type="number" min="1" name="unit_prices[]"
                                                                       value="{{ old('unit_prices')[$key] }}"
                                                                       id="product_price_{{ $key+1 }}"
                                                                       class="dynamic_product_price form-control"></td>
                                                            <td>
                                                                <input type="number" class="form-control" min="1"
                                                                       value="{{ old('quantities')[$key]  }}" step="1"
                                                                       id="quantity_{{ $key+1 }}" name="quantities[]"
                                                                       class="form-control changesNo" autocomplete="off"
                                                                       placeholder="Qty"
                                                                       onkeyup="get_sub_total(this.value, {{ $key+1 }})">
                                                            </td>
                                                            {{--                                                                  <td>--}}
                                                            {{--                                                                      <div id="product_unit_1"></div>--}}
                                                            {{--                                                                  </td>--}}
                                                            <td><input type="number" name="product_sub_total[]"
                                                                       value="{{ old('product_sub_total')[$key]  }}"
                                                                       id="sub_total_{{ $key+1 }}" readonly="readonly"
                                                                       min="0" class="form-control totalLinePrice"
                                                                       autocomplete="off" placeholder="Sub Total">
                                                            </td>
                                                            <td>
                                                                <button class="remove btn btn-danger"><span
                                                                            class="fa fa-trash-o"></span></button>
                                                            </td>
                                                        </tr>

                                                    @endforeach

                                                @else

                                                    <tr>
                                                        <td>
                                                            <select name="product_ids[]" id="product_1"
                                                                    class="dynamic_product form-control select2"
                                                                    onchange="show_product_price(this.value, 1)">
                                                                <option value="0">select</option>
                                                                {{--@foreach ($products as $product)--}}
                                                                    {{--<option value="{{ $product->id }}">{{ $product->product_name }}</option>--}}
                                                                {{--@endforeach--}}
                                                            </select>
                                                        </td>
                                                        <td><input type="number" min="1" name="unit_prices[]" value=""
                                                                   id="product_price_1"
                                                                   class="dynamic_product_price form-control"></td>
                                                        <td>
                                                            <input type="number" class="form-control" min="1" step="1"
                                                                   id="quantity_1" name="quantities[]" id="quantities"
                                                                   class="form-control changesNo" autocomplete="off"
                                                                   placeholder="Qty"
                                                                   onkeyup="get_sub_total(this.value, 1)">
                                                        </td>
                                                        {{--                                                              <td>--}}
                                                        {{--                                                                  <div id="product_unit_1"></div>--}}
                                                        {{--                                                              </td>--}}
                                                        <td><input type="number" name="product_sub_total[]"
                                                                   id="sub_total_1" readonly="readonly" min="0"
                                                                   id="txtResult" class="form-control totalLinePrice"
                                                                   autocomplete="off" placeholder="Sub Total">
                                                        </td>
                                                        <td></td>

                                                    </tr>

                                                    @endif

                                                    </tbody>
                                            </table>
                                            <span class="btn btn-sm btn-primary pull-right addRow"><i
                                                        class="fa fa-plus"></i> Add New</span>

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
                                               value="{{ old('sub_total') ? : 0 }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="">Discount(Deduction)</label>
                                        <input type="number" class="form-control" name="invoice_discount"
                                               id="invoice_discount" value="{{ old('invoice_discount') ? : 0 }}"
                                               onkeyup="deduct_discount(this.value)">
                                    </div>

                                    <div class="form-group">
                                        <label for="">Tax/Others (Addition)</label>
                                        <input type="number" class="form-control" name="invoice_tax" id="invoice_tax"
                                               value="{{ old('invoice_tax') ? : 0 }}" onkeyup="add_tax(this.value)">
                                    </div>

                                    <div class="form-group">
                                        <label for="">Total Payable</label>
                                        <input type="number" class="form-control" name="total_payable"
                                               id="total_payable" value="{{ old('total_payable') ? : 0 }}"
                                               readonly="readonly">
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-8">
                                            <button class="btn btn-primary btn-sm" type="submit"><i
                                                        class="fa fa-fw fa-lg fa-check-circle"></i> Add Quotation
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

@endsection

@section('footer-script')
    @include('admin.includes.date_field')
    <script type="text/javascript" src="{{asset('jq/select2Loads.js')}}"></script>
    <script type="text/javascript">
        // $('.select2').select2();
    </script>
    <script>
        select2Loads({
            selector: '.customer_id',
            url: "/people/customers",
        })

        $(() => {
            triggerProduceSelect()
        })

        function triggerProduceSelect() {

            select2Loads({
                selector: '.dynamic_product',
                url: "/products",
            })
        }
    </script>

    <script>


        $('.addRow').on('click', function () {

            addRow('product_table');

        });

        var i = 1;

        function addRow(tableId) {
            i++;
            var table = document.getElementById(tableId);
            var tr = '<tr>' +
                '<td><select name=\"product_ids[]\" id=\"product_name_' + i + '\" class=\"dynamic_product ' +
                'form-control select2\" onchange=\"show_product_price(this.value, ' + i + ')\"><option>Select</option></select>' +
                '</td>' +
                '<td><input type=\"number\" name=\"unit_prices[]\" id=\"product_price_' + i + '\" placeholder=\"\" class=\"dynamic_product_price form-control\"></td>' +
                '<td><input type=\"number\" placeholder=\"Qty\" name=\"quantities[]\" id=\"quantity_' + i + '\" onkeyup=\"get_sub_total(this.value,' + i + ')\" class=\"form-control\"></td>' +
                // '<td><div id="product_unit_'+i+'"></div></td>'+
                '<td><input type=\"number\" readonly=\"readonly\" placeholder=\"Sub Total\" name=\"product_sub_total[]\" id=\"sub_total_' + i + '\" class=\"form-control\" ></td>' +
                '<td><button class=\"remove btn btn-sm btn-danger\"><span class=\"fa ' +
                'fa-trash-o\"></span></button></td>' +
                '</tr>';

            $('#product_table tbody').append(tr);
            triggerProduceSelect()

            $.ajax({
                url: "{{ url('products/get_json_list') }}",
                type: "GET",
                success: function (res) {
                    if (res) {
                        $.each(res, function (id, product_name) {
                            $("#product_name_" + i).append('<option value="' + id + '">' + product_name + '</option>');
                        });
                    }
                }
            });


        }

        $('tbody').on('click', '.remove', function () {
            $(this).parent().parent().remove();
        });


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

        function get_sub_total(qty, n) {
            $("#product_price_" + n).prop('readonly', true);
            var qty = parseInt(qty);
            var product_price = parseFloat($('#product_price_' + n).val());
            $("#sub_total_" + n).val(qty * product_price);
            var inv_sub_total = 0;
            for (var i = 1; i <= n; i++) {
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
            $('#invoice_discount').prop('readonly', true);
            var discount_amount = $('#invoice_discount').val();
            var tax_amount = tax_amount <= 0 ? 0 : tax_amount;
            var invoice_sub_total = $('#invoice_sub_total').val();
            var tax_added = (parseFloat(invoice_sub_total) + parseFloat(tax_amount)) - parseFloat(discount_amount);
            $('#total_payable').val(tax_added);
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


        $(document).ready(function () {

            $('form').on('focus', 'input[type=number]', function (e) {
                $(this).on('mousewheel.disableScroll', function (e) {
                    e.preventDefault()
                })
            });

            // Restore scroll on number inputs.
            $('form').on('blur', 'input[type=number]', function (e) {
                $(this).off('wheel');
            });

            // Disable up and down keys.
            $('form').on('keydown', 'input[type=number]', function (e) {
                if (e.which == 38 || e.which == 40)
                    e.preventDefault();
            });

        });

    </script>
@stop