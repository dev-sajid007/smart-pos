

<div class="col-md-3">
    <div class="bs-component">
        <div class="card">
            <div class="card-body" style="padding-top: 8px !important;">

                <!-- Subtotal -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="font-weight-bold input-group-text">Invoice Total</span>
                    </div>
                    <input type="text" readonly class="form-control invoice-total height-30" name="invoice_total" value="{{ old('invoice_total', $sale->sale_details->sum('product_sub_total')) }}">
                </div>

                <!-- Invoice Tax -->
                @if ($productTax)
                    <div class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="font-weight-bold input-group-text">Invoice Tax</span>
                        </div>
                        <input type="text" name="invoice_tax" value="{{ old('invoice_tax', $sale->invoice_tax) }}" readonly class="invoice-tax form-control height-30">
                    </div>
                @else
                    <input type="hidden" name="invoice_tax" value="{{ old('invoice_tax', $sale->invoice_tax) }}" readonly class="invoice-tax">
                @endif

                <!--  CURRIER AMOUNT  -->
                @if($settings->where('title', 'Courier Service For Sale')->where('options', 'yes')->count() > 0)
                    <div class="input-group currier-amount mb-1">
                        <div class="input-group-prepend">
                            <span class="font-weight-bold input-group-text">Currier Amount</span>
                        </div>
                        <input type="text" name="currier_amount" value="{{ old('currier_amount', $sale->currier_amount) }}" class="form-control input-currier-amount height-30" autocomplete="off" placeholder="Currier Service Amount" onkeyup="calculateTotal()"  onkeypress="return IsNumeric(event);">
                    </div>
                @else
                    <input type="hidden" name="currier_id" value="{{ old('currier_amount', $sale->currier_id) }}">
                    <input type="hidden" class="input-currier-amount" name="currier_amount" value="{{ old('currier_amount', $sale->currier_amount) }}">
                @endif

                <!-- Discount -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span title="Deduction" class="input-group-text font-weight-bold">Discount</span>
                    </div>
                    <input type="text" name="discount_percent" value="{{ old('discount_percent', (($sale->invoice_discount * 100) / $sale->sub_total)) }}" class="form-control text-center discount-percent height-30" placeholder="%"  onkeypress="return IsNumeric(event);">
                    <input type="text" name="invoice_discount" value="{{ old('invoice_discount', $sale->invoice_discount) }}" class="form-control text-center discount-amount height-30" placeholder="Amount" onkeypress="return IsNumeric(event);">
                </div>

                <!-- COD -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span title="Cash On Delivery" class="input-group-text font-weight-bold">COD</span>
                    </div>
                    <input type="text" name="cod_percent" value="{{ old('cod_percent', $sale->cod_percent) }}" class="form-control text-center cod-percent height-30" onkeyup="calculateCODAmount(this)" placeholder="%" onkeypress="return IsNumeric(event);">
                    <input type="text" name="cod_amount" value="{{ old('cod_amount', $sale->cod_amount) }}" readonly class="form-control text-center cod-amount height-30" placeholder="Amount">
                </div>

                <!-- Payable Amount -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <label class="font-weight-bold input-group-text"title="Payment Amount">P. Amounts</label>
                    </div>
                    <input type="text" name="payable_amount" value="{{ old('payable_amount', ($sale->total_payable - ($sale->cod_amount ?? 0))) }}" readonly class="form-control payable-amount height-30">
                </div>

                <!-- Customer Point -->
                @if ($customerPoint)
                    <div class="input-group mb-1">
                        <div class="input-group-prepend">
                            <label class="font-weight-bold input-group-text" title="Customer Point">Customer Point</label>
                        </div>
                        <input type="text" name="customer_point_amount" value="{{ old('customer_point_amount', $sale->point_amount) }}" readonly class="form-control customer-point height-30">
                    </div>
                @endif

                <!-- Previous Due -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <label class="font-weight-bold input-group-text">Previous Due</label>
                    </div>
                    <input type="text" name="previous_due" value="{{ old('previous_due', ($sale->previous_due > 0 ? $sale->previous_due : 0 )) }}" readonly class="form-control previous-due height-30">
                </div>

                <!-- Advance Payment -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <label class="font-weight-bold input-group-text">Advanced</label>
                    </div>
                    <input type="text" name="advanced_payment" value="{{ old('advanced_payment', ($sale->previous_due < 0 ? abs($sale->previous_due) : 0 )) }}" readonly class="form-control advanced-payment height-30">
                </div>

                <!-- Total Payable -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <label class="font-weight-bold input-group-text">Total Payable</label>
                    </div>
                    <input type="text" name="total_payable" value="{{ old('total_payable', $sale->balance + $sale->receive_amount) }}" readonly class="form-control total-payable-amount height-30">
                </div>


                <!-- Receive Amount -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <label class="font-weight-bold input-group-text">Receive Amount</label>
                    </div>
                    <input type="text" name="receive_amount" value="{{ old('receive_amount', $sale->receive_amount) }}" class="form-control receive-amount height-30" autocomplete="off"  onkeypress="return IsNumeric(event);">
                </div>

                <!-- Current Balance -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <label class="font-weight-bold input-group-text">Current Balance</label>
                    </div>
                    <input type="text" name="current_balance" value="{{ old('current_balance', $sale->balance) }}" readonly class="form-control current-balance height-30">
                </div>

                <!-- Account Info -->
                <div class="input-group mb-1">
                    <select name="account_information" id="account_info" class="select2 select-account account-info">
                        @foreach($account_infos as $account)
                            <option value="{{ $account->id }}" data-total-amount ="{{ $account->total_amount }}">{{ $account->account_name .' , '. $account->account_no }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Account Balance -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend" style="height: 30px !important;">
                        <label class="font-weight-bold input-group-text" style="width: 120px !important;">Account Balance:</label>
                    </div>
                    <input type="text" class="form-control account-balance" style="height: 30px !important;" readonly value="{{ optional($account_infos->first())->total_amount }}">
                </div>

                <!-- Received By -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend" style="height: 30px !important;">
                        <label class="font-weight-bold input-group-text" style="width: 120px !important;">Receive By</label>
                    </div>
                    <input type="text" class="form-control receiver" style="height: 30px !important;" name="receiver" value="{{ old('received_by') }}" placeholder="Order Received By">
                    <input type="hidden" name="received_by">
                </div>

                <!-- Delivered By -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend" style="height: 30px !important;">
                        <label class="font-weight-bold input-group-text" style="width: 120px !important;">Delivered By</label>
                    </div>
                    <input type="text" class="form-control deliverer" style="height: 30px !important;" name="deliverer" value="{{ old('deliverer') }}" placeholder="Order Delivered By">
                    <input type="hidden" name="delivered_by">
                </div>

                <!-- Print option -->
                <div class="form-group mx-auto">
                    <div class="animated-radio-button mt-2 mx-auto">
                        <label>
                            <input type="radio" name="print_type" {{ $is_default_pos_print ? 'checked' : '' }} value="1"><span class="label-text">POS Print</span>
                        </label>
                        &nbsp;
                        <label>
                            <input type="radio" name="print_type" value="0" {{ $is_default_pos_print ? '' : 'checked' }}><span class="label-text">Normal Print</span>
                        </label>
                    </div>
                </div>


                <!-- Form option -->
                <div class="row px-3">
                    <div class="col-sm-7" style="padding: 0 !important;">
                        <button type="button" class="btn btn-sm btn-primary btn-block save-btn"><i class="fa fa-edit"></i>  Update</button>
                    </div>
                    <div class="col-sm-4 ml-auto" style="padding: 0 !important;">
                        <a href="{{ route('sales.index') }}" class="btn btn-sm btn-success btn-block" style="width: 100%">List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
