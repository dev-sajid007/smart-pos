@extends('admin.master')

@section('title', ' - Stock Transfer')

@push('style')
@endpush

@section('content')


    <main class="app-content">
        <form class="form-horizontal" id="purchaseForm" method="POST" action="{{ route('warehouse-to-warehouses.store') }}">
            @csrf
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1 mx-auto">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <i class="fa fa-plus"></i> Warehouse To Warehouse Stock Transfer
                            <a href="{{ route('warehouse-to-warehouses.index') }}" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-eye"></i> Transfer List
                            </a>
                        </div>

                        <div class="card-body">


                        @include('partials._alert_message')


                        <!-- filter section -->
                            <div class="row mb-2">
                                <div class="col-md-4 pr-0">
                                    <div class="row supplier-row">
                                        <div class="">
                                            <span class="input-group-text" style="background: transparent; border: none">From Warehouse</span>
                                        </div>
                                        <div style="min-width: 250px">
                                            <select name="from_warehouse_id" class="form-control select-from-warehouse select2 mr-0" style="width: 100%">
                                                <option value="">Show Room</option>
                                                @foreach($warehouses as $id => $warehouse)
                                                    <option value="{{ $id }}" {{ old('from_warehouse_id') == $id ? 'selected' : '' }}>{{ $warehouse }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 pr-0">
                                    <div class="row supplier-row">
                                        <div class="">
                                            <span class="input-group-text" style="background: transparent; border: none">To Warehouse</span>
                                        </div>
                                        <div style="min-width: 250px">
                                            <select name="to_warehouse_id" class="form-control select-to-warehouse select2 mr-0" style="width: 100%">
                                                <option value="">Show Room</option>
                                                @foreach($warehouses as $id => $warehouse)
                                                    <option value="{{ $id }}" {{ old('to_warehouse_id') == $id ? 'selected' : '' }}>{{ $warehouse }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 pr-0">
                                    <div class="row supplier-row">
                                        <div class="">
                                            <span class="input-group-text" style="background: transparent; border: none">Date</span>
                                        </div>
                                        <div style="min-width: 250px">
                                            <input name="date" value="{{ old('date', date('Y-m-d')) }}" class="form-control dateField mr-0" style="width: 100%" type="text" placeholder="Date">
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <br>
                                <br>
                            </div>


                            <!-- product entry section -->
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="40%">Product Name</th>
                                                <th width="20%">Quantity</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select class="form-control select-product select2 mr-0" style="width: 100%">
                                                        <option value="">Select</option>
                                                        @foreach($products as $key => $product)
                                                            <option value="{{ $product->id }}"
                                                                    data-product-name="{{ $product->product_name }}"
                                                                    data-product-code="{{ $product->product_code }}"
                                                                    data-product-unit="{{ optional($product->product_unit)->name }}"
                                                                    data-available-quantity="{{ $product->total_available_stock }}"
                                                            >
                                                                {{ $product->product_name }} ({{ $product->product_code }}) [{{ $product->total_available_stock }}]
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="select-quantity form-control" type="text" value="">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                            <!-- product display section -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="3%">Sl.</th>
                                                <th>Product Name</th>
                                                <th>Product Code</th>
                                                <th>Product Unit</th>
                                                <th width="10%">Product Qty</th>
                                                <th width="5%"></th>
                                            </tr>
                                        </thead>

                                        <tbody class="product-display">

                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <th colspan="4"><strong>Total:</strong></th>
                                                <th class="text-center total-quantity"></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="btn-group btn-corner pull-right">
                                        <a href="{{ route('warehouse-to-warehouses.index') }}" class="btn btn-success"><i class="fa fa-list"></i> List</a>
                                        <button type="button" class="btn btn-primary save-btn"><i class="fa fa-save"></i> Save</button>
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
    <script type="text/javascript">


        $('.select-product').change(function () {
            $('.select-product').select2();
        })


        $('.select-quantity').keyup(function(event) {
            let keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13') {
                insertNewItem()
            }
        });

        function insertNewItem () {
            let selected_item = $('.select-product').find('option:selected');
            let productId = selected_item.val()
            let productName = selected_item.data('product-name')
            let productCode = selected_item.data('product-code')
            let productUnit = selected_item.data('product-unit')
            let availableQuantity = selected_item.data('available-quantity')

            let quantity = $('.select-quantity').val()
            if (Number(availableQuantity) < Number(quantity)) {
                alert('You do not have enough stock')
                return false
            }

            if (productId != '' && quantity != '') {
                let tr =    `<tr>
                                <td class="item-serial"></td>
                                <td>${ productName }<input type="hidden" name="product_ids[]" class="product-id" value="${ productId }"></td>
                                <td>${ productCode }</td>
                                <td>${ productUnit }</td>
                                <td><input type="text" readonly name="quantities[]" class="product-quantity form-control text-center" value="${ quantity }"></td>
                                <td class="text-right">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>`

                $('.product-display').append(tr)

                setItemSerial()

                // disable selected option
                $('.select-product option[value=' + productId + ']').prop("disabled", true);
                $('.select-quantity').val('');
                $('.select-product').val('');
                $('.select-product').select2('focus');
                $('.select-product').select2();
            } else {
                alert('Select product and add quantity')
            }
        }

        function setItemSerial() {
            let total = 0
            $('.item-serial').each(function (index) {
                $(this).text(index+1)
                total += parseFloat($(this).closest('tr').find('.product-quantity').val())
            })
            $('.total-quantity').text(total.toFixed(2))
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
            if ($('.item-serial').length == 0) {
                alert('Please purchase at least one product')
            } else if ($('.select-from-warehouse').val() == $('.select-to-warehouse').val()) {
                alert('Please select different warehouse for stock transfer')
            } else {
                $('#purchaseForm').submit()
            }
        })

    </script>


@endsection
