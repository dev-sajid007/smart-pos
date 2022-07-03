@extends('admin.master')

@section('title', ' - Purchase')

@push('style')
    <style>
        .form-group {margin-bottom: 8px;}
        .item-box {padding: 0px 4px;height: 35px;}
        .add-new-product-button{padding: 4.5px;border-radius: 0px 5px 5px 0px;visibility: hidden;}
        .loading{display: none;justify-content: center;align-items: center;width: 100%;position: absolute;height: 100%;z-index: 11111;background: #ffffffc7;}
    </style>
@endpush

@section('content')

    @include('admin.purchases.quickadd', ['errors' => $errors])

    <main class="app-content">
        <form class="form-horizontal" id="purchaseForm" method="POST" action="{{ route('purchases.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <i class="fa fa-plus"></i> Add New Purchase
                                    <a href="{{ route('purchases.index') }}" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-eye"></i> Purchase List
                                    </a>
                                </div>

                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-sm-12">
                                            <table class="table table-sm table-bordered-none">
                                                <thead>
                                                    <tr>
                                                        <th style="border: none" width="45%"><span onclick="openCreateModal()" title="Add New Product" data-toggle="modal" style="cursor: pointer" data-target="#addNew" class="text-primary">Supplier</span></th>
                                                        <th style="border: none" width="35%"><a href="{{ route('warehouses.create') }}" target="_blank">Warehouse</a></th>
                                                        <th style="border: none" width="20%">Purchase Date</th>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <select name="fk_supplier_id" class="form-control mr-0" id="supplier" onchange="get_supplier_product()" placeholder="Select Supplier"> </select>
                                                        </td>
                                                        <td>
                                                            <select name="warehouse_id" class="form-control select-warehouse select2 mr-0" style="width: 100%">
                                                                <option value="">Show Room</option>
                                                                @foreach($warehouses as $id => $warehouse)
                                                                    <option value="{{ $id }}" {{ old('warehouse_id') == $id ? 'selected' : '' }}>{{ $warehouse }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" class="form-control dateField" type="text" placeholder="Date">
                                                        </td>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <br>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-sm ">
                                                <thead>
                                                    <tr>
                                                        <th>Product Name</th>
                                                        <th width="15%">Unit Cost</th>
                                                        <th width="12%">Quantity</th>
                                                        <th width="12%">Free QTY</th>
                                                        <th width="18%" class="text-right">Sub Total</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="supplier_product">
                                                    @if (old('product_ids'))
                                                        @foreach(old('product_ids') as $key => $value)
                                                            <tr>
                                                                <td style="display: none">
                                                                    <input type="text" tabindex="-1" readonly
                                                                           name="product_ids[]" id="product_id_1"
                                                                           class="form-control item-box"
                                                                           value="{{ old('product_ids')[$key] }}">
                                                                </td>
                                                                <td>
                                                                    <textarea name="product_name[]" tabindex="-1"
                                                                              class="form-control item-box" readonly
                                                                              id="product_name_1"
                                                                    >{{ old('product_name')[$key] }}</textarea>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="unit_cost[]"
                                                                           id="product_cost_1" readonly min="1"
                                                                           value="{{ old('unit_cost')[$key] }}"
                                                                           class="form-control item-box">
                                                                </td>
                                                                <td style="display: none">
                                                                    <input type="number" tabindex="-1" name="unit_prices[]"
                                                                           readonly min="1"
                                                                           value="{{ old('unit_prices')[$key] }}"
                                                                           id="product_price_1"
                                                                           class="dynamic_product_price form-control item-box"
                                                                           onblur="">
                                                                </td>
                                                                <td>
                                                                    <input type="number" min="0" step="1"
                                                                           value="{{ old('quantities')[$key] ?: '0' }}"
                                                                           id="quantity_1" name="quantities[]"
                                                                           class="form-control changesNo  qty_unit item-box"
                                                                           autocomplete="off" placeholder="Qty"
                                                                           onkeyup="get_sub_total(this.value, 1)">
                                                                </td>

                                                                <td>
                                                                    <input type="number" min="0" step="1"
                                                                           value="{{ old('free_quantities')[$key] ?: '0' }}"
                                                                           id="free_quantity_1" name="free_quantities[]"
                                                                           class="form-control changesNo  free_qty_unit item-box"
                                                                           autocomplete="off" placeholder="Qty">
                                                                </td>

                                                                <td>
                                                                    <input type="number" tabindex="-1"
                                                                           name="product_sub_total[]"
                                                                           value="{{ old('product_sub_total')[$key] ?: '0' }}"
                                                                           id="sub_total_1" readonly="readonly" min="0"
                                                                           class="form-control totalLinePrice item-box text-right"
                                                                           autocomplete="off" placeholder="Sub Total">
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td style="display: none">
                                                                <input type="text" tabindex="-1" readonly
                                                                       name="product_ids[]" id="product_id_1"
                                                                       class="form-control item-box" autofocus value="">
                                                            </td>
                                                            <td>
                                                                <textarea name="product_name[]" tabindex="-1"
                                                                          class="form-control item-box" readonly
                                                                          id="product_name_1"
                                                                ></textarea>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="unit_cost[]"
                                                                       id="product_cost_1" readonly min="1" value=""
                                                                       class="form-control item-box">
                                                            </td>
                                                            <td style="display: none">
                                                                <input type="number" tabindex="-1" name="unit_prices[]"
                                                                       readonly min="1" value="" id="product_price_1"
                                                                       class="dynamic_product_price form-control item-box"
                                                                       onblur="">
                                                            </td>
                                                            <td>
                                                                <input type="number" min="1" step="1" value=""
                                                                       id="quantity_1" name="quantities[]"
                                                                       class="form-control changesNo  qty_unit item-box"
                                                                       autocomplete="off" placeholder="Qty"
                                                                       onkeyup="get_sub_total(this.value, 1)">
                                                            </td>
                                                            <td>
                                                                <input type="number" min="1" step="1" value=""
                                                                       id="free_quantity_1" name="free_quantities[]"
                                                                       class="form-control changesNo  free_qty_unit item-box"
                                                                       autocomplete="off" placeholder="Free Qty">
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="number" tabindex="-1"
                                                                       name="product_sub_total[]" value="" id="sub_total_1"
                                                                       readonly="readonly" min="0"
                                                                       class="form-control totalLinePrice item-box text-right"
                                                                       autocomplete="off" placeholder="Sub Total">
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header bg-primary text-white"> Total </div>
                                <div class="card-body" style="padding-top: 8px !important;">
                                    <div class="form-group">
                                        <label style="margin-bottom: 0">Total</label>
                                        <input type="number" tabindex="-1" name="subtotal" value="{{ old('subtotal', 0) }}" id="subtotal"
                                               class="form-control item-box" readonly
                                               placeholder="Total">
                                    </div>
                                    <div class="form-group">
                                        <label style="margin-bottom: 0">Discount (Deduction)</label>
                                        <input type="number" class="form-control" name="invoice_discount"
                                               id="invoice_discount" value="{{ old('invoice_discount', 0) }}">
                                    </div>
                                    <div class="form-group">
                                        <label style="margin-bottom: 0">Tax/Others (Addition)</label>
                                        <input type="number" class="form-control" name="invoice_tax" id="invoice_tax"
                                               value="{{ old('invoice_tax', 0) }}" >
                                    </div>
                                    <div class="form-group advance-group">
                                        <label style="margin-bottom: 0">Advance</label>
                                        <input type="number" class="form-control" tabindex="-1" name="advanced" readonly
                                               id="advance" value="{{ old('advanced', 0) }}">
                                    </div>
                                    <div class="form-group prevDue-group">
                                        <label style="margin-bottom: 0">Previous Due</label>
                                        <input type="number" class="form-control" name="previous_due" tabindex="-1" readonly id="previous_due" value="{{ old('previous_due') }}">
                                    </div>
                                    <div class="form-group">
                                        <label style="margin-bottom: 0">Total Payable</label>
                                        <input type="number" tabindex="-1" name="total_payable" value="{{ old('total_payable') }}" class="form-control"
                                               id="total_payable_temp"
                                               readonly="readonly"
                                               style="font-size: 24px;font-weight: bolder; color: #0d1214;">

                                    </div>
                                    <div class="form-group">
                                        <label style="margin-bottom: 0">Given Amount</label>
                                        <input type="text" name="total_paid_amount" id="total_paid"
                                               value="{{ old('total_paid_amount', 0) }}" class="form-control given-amount"
                                               onkeyup="">

                                    </div>

                                    <div class="form-group">
                                        <label for="account_info" style="margin-bottom: 0 !important;">Account Information</label>
                                        <select name="account_id" id="account_info" class="select2 select-account account-info">
                                            @foreach($account_infos as $account)
                                                <option value="{{ $account->id }}" {{ old('account_id') == $account->id || $account->default_account == 1 ? 'selected' : '' }} data-total-amount = "{{ $account->total_amount }}">{{ $account->account_name .' , '. $account->account_no }}</option>
                                            @endforeach
                                        </select>
                                        <p class="account-balance py-0 my-0">{{ $account_infos->first()->total_amount }}</p>
{{--                                        @if (old('account_id'))--}}
{{--                                        <p class="account-balance py-0 my-0">{{ $account_infos->where('id', old('account_id'))->total_amount }}</p>--}}
{{--                                        @else--}}
{{--                                            <p class="account-balance py-0 my-0">{{ $account_infos->where('default_account', 1)->total_amount ?? $account_infos->first()->total_amount }}</p>--}}
{{--                                        @endif--}}
                                    </div>

                                    <!-- Action -->
                                    <div class="row px-3">
                                        <div class="col-sm-7" style="padding: 0 !important;">
                                            <button type="button" class="btn btn-sm btn-primary btn-block save-btn"><i class="fa fa-save"></i> Save</button>
                                        </div>
                                        <div class="col-sm-4 ml-auto" style="padding: 0 !important;">
                                            <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-success btn-block" style="width: 100%">List</a>
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
    @include('admin.includes.date_field')
@endsection

@section('footer-script')
    <script type="text/javascript" src="{{ asset('jq/select2Loads.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/jq/purchase.js') }}"></script>

    <script>
        $('.select-account').change(function () {
            if ($('.select-account').val() == '') {
                $('.account-balance').text('')
            } else {
                $('.account-balance').text($('.select-account option:selected').data('total-amount'))
            }
        })

        // prevent given amount is not greater than account balance
        $('.given-amount').keyup(function () {
            let amount = $(this).val()
            let accountBalance = $('.account-balance').text()
            if (amount > 0 && accountBalance == '') {
                alert('Please select an account first')
                $(this).val(0)
            } else if (parseFloat(amount) > parseFloat(accountBalance)) {
                alert('Payment amount cant be up to ' + $('.select-account option:selected').text() +  ' account balance')
                $(this).val(0)
            }
        })

        $('.save-btn').click(function () {
            let isSubmit = true
            if ($('.given-amount').val() > 0) {
                if ($('.account-info').val() == '') {
                    isSubmit = false
                }
            }
            if (isSubmit == true) {
                $('#purchaseForm').submit()
            } else {
                alert('Please select transaction account')
            }
        })
    </script>
@stop
