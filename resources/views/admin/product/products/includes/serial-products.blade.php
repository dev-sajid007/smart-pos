
<div class="form-group row">
    <label class="control-label col-md-3">Is Serial Product?</label>
    <div class="col-md-8">
        <div class="animated-checkbox">
            <label>
                <input type="checkbox" {{ old('has_serial') ? 'checked' : '' }} name="has_serial" id="has_serial" class="form-control check-all">
                <span class="label-text">Yes</span>
            </label>
        </div>
    </div>
</div>

<div class="form-group row {{ old('has_serial') ? 'checked' : 'd-none' }} generate-from-common">
    <label class="control-label col-md-4">Generate From Common</label>
    <div class="col-md-8">
        <div class="animated-checkbox">
            <label>
                <input type="checkbox" name="is_common_generated" {{ old('is_common_generated') ? 'checked' : '' }} id="common_serial" class="form-control check-all">
                <span class="label-text">Yes</span>
            </label>
        </div>
    </div>
</div>


<div class="form-group row common-serial-row {{ old('is_common_generated', 'off') == 'on' && old('has_serial') ? '' : 'd-none' }}">
    <div class="col-md-4">
        <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><b>Common</b></span>
            </div>
            <input type="text" name="common_serial" value="{{ old('common_serial') }}"
                   placeholder="Common match serial"
                   class="form-control input-common-serial" aria-label="Small"
                   aria-describedby="inputGroup-sizing-sm">
        </div>
    </div>

    <div class="col-md-7">
        <div class="input-group input-group-sm mb-3">
            <input type="text" name="serial_from"
                   onkeypress="return onlyNumber(event)"
                   value="{{ old('serial_from') }}"
                   class="form-control input-start-from" placeholder="Start From">

            <div class="input-group-prepend">
                <span class="input-group-text"><b>From | To</b></span>
            </div>

            <input type="text" name="serial_to"
                   onkeypress="return onlyNumber(event)"
                   value="{{ old('serial_to') }}"
                   class="form-control input-start-from" placeholder="End To">
        </div>
    </div>
</div>

<div class="form-group row product-serials {{ old('is_common_generated', 'off') == 'off' && old('has_serial') ? '' : 'd-none' }}">
    <label class="control-label col-md-3">Product Serials</label>
    <div class="col-md-8">
        <textarea class="form-control input-product-serial" placeholder="serial1, serial2, . . . . . . . . . . . . . . . . ." name="product_serials" style="height: 70px">{{ old('product_serials') }} </textarea>
        @error('product_serials')
        <span class="table-danger">{{ $message }}</span>
        @enderror
    </div>
</div>
