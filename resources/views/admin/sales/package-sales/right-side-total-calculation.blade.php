@php 
    $customerDue = optional($customers->where('default', 1)->first())->balance ?? 0; 
    if ($customerDue < 0) {
        $previousDue    = 0;
        $advance        = abs($customerDue);
    } else {
        $previousDue    = $customerDue;
        $advance        = 0;
    }
@endphp


<div class="col-md-3">
    <div class="bs-component">
        <div class="card">
            <div class="card-body" style="padding-top: 8px !important;">

                <!-- Subtotal -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="font-weight-bold input-group-text">Invoice Total</span>
                    </div>
                    <input type="text" readonly class="form-control invoice-total height-30"  name="invoice_total" value="{{ old('invoice_total') }}">
                </div>

                <!-- Invoice Tax -->
                @if ($productTax)
                    <div class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="font-weight-bold input-group-text">Invoice Tax</span>
                        </div>
                        <input type="text" name="invoice_tax" value="{{ old('invoice_tax') }}" readonly class="invoice-tax form-control height-30">
                    </div>
                @else
                    <input type="hidden" name="invoice_tax" value="" readonly class="invoice-tax">
                @endif

                <!--  CURRIER AMOUNT  -->
                @if($settings->where('title', 'Courier Service For Sale')->where('options', 'yes')->count() > 0)
                    <div class="input-group currier-amount mb-1">
                        <div class="input-group-prepend">
                            <span class="font-weight-bold input-group-text">Currier Amount</span>
                        </div>
                        <input type="text" name="currier_amount" value="{{ old('currier_amount') }}" class="form-control input-currier-amount height-30" autocomplete="off" placeholder="Currier Service Amount" onkeyup="calculateTotal()" onkeypress="return event.charCode >= 46 && event.charCode <= 57">
                    </div>
                @else
                    <input type="hidden" class="select-currier" name="currier_id" value="">
                    <input type="hidden" class="input-currier-amount" name="currier_amount" value="">
                @endif


                <!-- Discount -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span title="Deduction" class="input-group-text font-weight-bold">Discount</span>
                    </div>
                    <input type="text" name="invoice_discount" value="{{ old('invoice_discount') }}" class="form-control text-center discount-amount height-30" placeholder="Amount" onkeypress="return event.charCode >= 46 && event.charCode <= 57">
                    <input type="text" name="discount_percent" value="{{ old('discount_percent') }}" class="form-control text-center discount-percent height-30" placeholder="%" onkeypress="return event.charCode >= 46 && event.charCode <= 57">
                </div>

                <!-- Payment Amount -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <label class="font-weight-bold input-group-text" title="Payment Amount">P. Amounts</label>
                    </div>
                    <input type="text" name="payable_amount" value="{{ old('payable_amount') }}" readonly class="form-control payable-amount height-30">
                </div>

                <!-- Customer Point -->
                @if ($customerPoint)
                    <div class="input-group mb-1">
                        <div class="input-group-prepend">
                            <label class="font-weight-bold input-group-text" title="Customer Point">Customer Point</label>
                        </div>
                        <input type="text" name="customer_point_amount" value="{{ old('customer_point_amount') }}" readonly class="form-control customer-point height-30">
                    </div>
                @endif

                <!-- Previous Due -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <label class="font-weight-bold input-group-text">Previous Due</label>
                    </div>
                    <input type="text" name="previous_due" value="{{ old('previous_due', $previousDue) }}" readonly class="form-control previous-due height-30">
                </div>

                <!-- Advance Payment -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <label class="font-weight-bold input-group-text">Advanced</label>
                    </div>
                    <input type="text" name="advanced_payment" value="{{ old('advanced_payment', $advance) }}" readonly class="form-control advanced-payment height-30">
                </div>

                <!-- Total Payable -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <label class="font-weight-bold input-group-text">Total Payable</label>
                    </div>
                    <input type="text" name="total_payable" value="{{ old('total_payable') }}" readonly class="form-control total-payable-amount height-30">
                </div>

                <!-- Receive Amount -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <label class="font-weight-bold input-group-text">Receive Amount</label>
                    </div>
                    <input type="text" name="receive_amount" value="{{ old('receive_amount') }}" class="form-control receive-amount height-30" autocomplete="off" onkeypress="return event.charCode >= 46 && event.charCode <= 57">
                </div>

                <!-- Current Balance -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <label class="font-weight-bold input-group-text">Current Balance</label>
                    </div>
                    <input type="text" name="current_balance" value="{{ old('current_balance') }}" readonly class="form-control current-balance height-30">
                </div>

                <!-- Account Info -->
                <div class="input-group mb-1">
                    <select name="account_id" id="account_info" class="select2 select-account account-info">
                        @foreach($account_infos as $account)
                            <option value="{{ $account->id }}" data-total-amount ="{{ $account->total_amount }}" {{ $account->default_account == 1 ? 'selected' : '' }}>{{ $account->account_name .' , '. $account->account_no }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Account Balance -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <label class="font-weight-bold input-group-text">Account Balance:</label>
                    </div>
                    <input type="text" class="form-control account-balance height-30" readonly value="{{ optional($account_infos->where('default_account', 1)->first())->total_amount ?? optional($account_infos->first())->total_amount }}">
                </div>

                <!-- Received By -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <label class="font-weight-bold input-group-text">Receive By</label>
                    </div>
                    <input type="text" name="receiver" value="{{ old('received_by') }}" class="form-control receiver height-30" placeholder="Order Received By">
                    <input type="hidden" name="received_by">
                </div>

                <!-- Delivered By -->
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <label class="font-weight-bold input-group-text">Delivered By</label>
                    </div>
                    <input type="text" name="deliverer" value="{{ old('deliverer') }}" class="form-control deliverer height-30" placeholder="Order Delivered By">
                </div>

                <!-- Print option -->
                <div class="form-group mx-auto">
                    <div class="animated-radio-button mt-2 mx-auto">
                        <label>
                            <input type="radio" name="print_type" {{ optional($settings->where('title', 'Sale Default Printing')->first())->options == 'pos-print' ? 'checked' : '' }} value="1"><span class="label-text">POS Print</span>
                        </label>
                        &nbsp;
                        <label>
                            <input type="radio" name="print_type" {{ optional($settings->where('title', 'Sale Default Printing')->first())->options != 'pos-print' ? 'checked' : '' }} value="0"><span class="label-text">Normal Print</span>
                        </label>
                    </div>
                </div>

                @if ($settings->where('title', 'Send Invoice Sms To Customer')->where('options', 'yes')->count())
                    <div class="form-group mx-auto" style="margin: 0; margin-top: -10px">
                        <div class="animated-checkbox">
                            <label>
                                <input type="checkbox" name="is_send_message" class="form-control check">
                                <span class="label-text">Send Message</span>
                            </label>
                        </div>
                    </div>
                @endif

                <!-- Form option -->
                <div class="row px-3">
                    <div class="col-sm-5" style="padding: 0 !important;">
                        <button type="button" class="btn btn-sm btn-primary btn-block save-btn" onclick="saveSaleInvoice()"><i class="fa fa-save"></i> Save</button>
                    </div>
                    
                    <div class="col-sm-5 ml-auto" style="padding: 0 !important;">
                        <a href="{{ route('sales.index') }}" class="btn btn-sm btn-success btn-block" style="width: 100%">List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
