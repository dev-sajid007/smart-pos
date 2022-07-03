
<div class="col-md-3">
    <div class="card">
        <div class="card-header bg-primary text-white"> Total </div>

        <div class="card-body" style="padding-top: 8px !important;">
            <div class="form-group">
                <label style="margin-bottom: 0">Total</label>
                <input type="text" tabindex="-1" name="subtotal" value="{{ old('subtotal', 0) }}" id="subtotal" class="form-control item-box" readonly placeholder="Total">
            </div>
            <div class="form-group">
                <label style="margin-bottom: 0">Discount (Deduction)</label>
                <input type="text" class="form-control grand-total-calculate" name="invoice_discount" id="invoice_discount" onkeypress="return event.charCode >= 46 && event.charCode <= 57" value="{{ old('invoice_discount', 0) }}">
            </div>
            <div class="form-group">
                <label style="margin-bottom: 0">Tax/Others (Addition)</label>
                <input type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57" class="form-control grand-total-calculate" name="invoice_tax" id="invoice_tax" value="{{ old('invoice_tax', 0) }}" >
            </div>
            <div class="form-group advance-group">
                <label style="margin-bottom: 0">Advance</label>
                <input type="number" class="form-control" tabindex="-1" name="advanced" readonly id="advance" value="{{ old('advanced', 0) }}">
            </div>
            <div class="form-group prevDue-group">
                <label style="margin-bottom: 0">Previous Due</label>
                <input type="number" class="form-control" name="previous_due" tabindex="-1" readonly id="previous_due" value="{{ old('previous_due') }}">
            </div>
            <div class="form-group">
                <label style="margin-bottom: 0">Total Payable</label>
                <input type="text" tabindex="-1" name="total_payable" value="{{ old('total_payable') }}" class="form-control" id="total_payable_temp" readonly style="font-size: 24px;font-weight: bolder; color: #0d1214;">
            </div>
            <div class="form-group">
                <label style="margin-bottom: 0">Given Amount</label>
                <input type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57" name="total_paid_amount" id="total_paid" value="{{ old('total_paid_amount', 0) }}" class="form-control given-amount grand-total-calculate">

            </div>

            <div class="form-group">
                <label for="account_info" style="margin-bottom: 0 !important;">Account Information</label>
                <select name="account_id" id="account_info" class="select2 select-account account-info">
                    @foreach($account_infos as $account)
                        <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }} data-total-amount ="{{ $account->total_amount ?? 0 }}">{{ $account->account_name .' , '. $account->account_no }}</option>
                    @endforeach
                </select>
                <p class="account-balance py-0 my-0">{{ $account_infos->first()->total_amount ?? 0 }}</p>
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
