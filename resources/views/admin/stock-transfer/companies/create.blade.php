@extends('admin.master')

@section('title', ' - Stock Transfer')

@push('style')
@endpush

@section('content')


    <main class="app-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 mx-auto">
                <div class="card">

                    <!-- header -->
                    <div class="card-header bg-primary text-white">
                        <i class="fa fa-plus"></i> Company To Company Stock Transfer
                        <a href="{{ route('company-to-companies.index') }}" class="btn btn-info btn-sm pull-right">
                            <i class="fa fa-eye"></i> Transfer List
                        </a>
                    </div>

                    <div class="card-body">

                        @include('partials._alert_message')

                        <!-- Filter section -->
                        <form action="" method="get">
                            <!-- filter section -->
                            <div class="row mb-2">
                                <div class="col-md-4 pr-0">
                                    <div class="row">
                                        <div class="">
                                            <span class="input-group-text" style="background: transparent; border: none">Company</span>
                                        </div>
                                        <div style="min-width: 250px">
                                            <select name="select_company_id" class="form-control select-company select2 mr-0" style="width: 100%">
                                                <option value="">Select Company</option>
                                                @foreach($companies as $id => $name)
                                                    <option value="{{ $id }}" {{ request('select_company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 pr-0">
                                    <div class="row ">
                                        <div class="">
                                            <span class="input-group-text" style="background: transparent; border: none">Warehouse</span>
                                        </div>
                                        <div style="min-width: 250px">
                                            <select name="select_warehouse_id" class="form-control select-warehouse select2 mr-0" onchange="this.form.submit()" style="width: 100%">
                                                <option value="">Show Room</option>
                                                @foreach($warehouses as $id => $warehouse)
                                                    <option value="{{ $id }}" {{ request('select_warehouse_id') == $id ? 'selected' : '' }}>{{ $warehouse }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 pr-0">
                                    <div class="row">
                                        <div class="">
                                            <span class="input-group-text" style="background: transparent; border: none">Date</span>
                                        </div>
                                        <div style="min-width: 250px">
                                            <input name="select_date" value="{{ request('select_date', date('Y-m-d')) }}" class="form-control select-date dateField mr-0" style="width: 100%" type="text" placeholder="Date">
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <br>
                                <br>
                            </div>
                        </form>


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
                                                            {{ $product->product_name }} ({{ $product->product_code }}) [{{ $product->total_available_stock ?? 0 }}]
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

                        <!-- Product form section -->
                        <form class="form-horizontal" id="storeForm" method="POST" action="{{ route('company-to-companies.store') }}">
                            @csrf

                            <input type="hidden" name="company_id"      value="{{ request('select_company_id') }}"      class="store-company">
                            <input type="hidden" name="warehouse_id"    value="{{ request('select_warehouse_id') }}"    class="store-warehouse">
                            <input type="hidden" name="date"            value="{{ request('select_date') }}"            class="store-date">

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

                                        <!-- dynamically loadable section -->
                                        <tbody class="product-display"></tbody>

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

                            <!-- Action -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="btn-group btn-corner pull-right">
                                        <button type="button" class="btn btn-primary save-btn"><i class="fa fa-save"></i> Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('admin.includes.date_field')
@endsection

@section('footer-script')
    <script type="text/javascript">


        $('.select-company').change(function () {
            $('.store-company').val($(this).val());
        })

        $('.select-date').change(function () {
            $('.store-date').val($(this).val());
        })

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

            let productId           = selected_item.val()
            let productName         = selected_item.data('product-name')
            let productCode         = selected_item.data('product-code')
            let productUnit         = selected_item.data('product-unit')
            let availableQuantity   = selected_item.data('available-quantity')
            let quantity            = $('.select-quantity').val()

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
            } else if ($('.store-company').val() == '') {
                alert('Please select company')
            } else {
                $('#storeForm').submit()
            }
        })

    </script>


@endsection
