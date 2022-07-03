

<!--   PURCHASE DATE  -->
<div class="form-group row add_asterisk">
    <label class="control-label col-md-3">Purchase Price</label>
    <div class="col-md-8">
        <input name="product_cost" onkeypress="return event.charCode >= 46 && event.charCode <= 57" value="{{ old('product_cost') }}" class="form-control" type="text" placeholder="Product Cost">
        <div class="text-danger">
            {{ $errors->has('product_cost') ? $errors->first('product_cost') : '' }}
        </div>
    </div>
</div>


<!--   SALE PRICE  -->
<div class="form-group row add_asterisk">
    <label class="control-label col-md-3">Sales Price</label>
    <div class="col-md-8">
        <input name="product_price" onkeypress="return event.charCode >= 46 && event.charCode <= 57" value="{{ old('product_price') }}" class="form-control" type="text" placeholder="Product Price">
        <div class="text-danger">
            {{ $errors->has('product_price') ? $errors->first('product_price') : '' }}
        </div>
    </div>
</div>


<!--  HOLE SALE PRICE  -->
@if ($settings->where('title', 'Hole Sale Price')->where('options', 'yes')->count() > 0)
    <div class="form-group row ">
        <label class="control-label col-md-3">Hole Sale Price</label>
        <div class="col-md-8">
            <input name="holesale_price" onkeypress="return event.charCode >= 46 && event.charCode <= 57" value="{{ old('holesale_price') }}" class="form-control" type="text" placeholder="Hole Sale Price">
            <div class="text-danger">
                {{ $errors->has('holesale_price') ? $errors->first('holesale_price') : '' }}
            </div>
        </div>
    </div>
@endif


<!--   ALERT QUANTITY -->
<div class="form-group row">
    <label class="control-label col-md-3">Alert Quantity</label>
    <div class="col-md-8">
        <input name="product_alert_quantity" value="{{ old('product_alert_quantity') ?? 10 }}" class="form-control" type="text" placeholder="Alert Quantity">
        <div class="text-danger">
            {{ $errors->has('product_alert_quantity') ? $errors->first('product_alert_quantity') : '' }}
        </div>
    </div>
</div>


<!--   REFERENCE NO  -->
<div class="form-group row">
    <label class="control-label col-md-3">Reference No</label>
    <div class="col-md-8">
        <input name="product_reference" value="{{ old('product_reference') }}" class="form-control" type="text" placeholder="Reference">
        <div class="text-danger">
            {{ $errors->has('product_reference') ? $errors->first('product_reference') : '' }}
        </div>
    </div>
</div>


<!--   PRODUCT TAX  -->
@if ($settings->where('title', 'Product Tax')->where('options', 'yes')->count() > 0)
    <div class="form-group row">
        <label class="control-label col-md-3">Tax(%)</label>
        <div class="col-md-8">
            <input name="tax" value="{{ old('tax') }}" class="form-control" onkeypress="return onlyNumber(event)" type="text" placeholder="Tax">
        </div>
    </div>
@endif

<!--   PRODUCT DISCOUNT  -->
@if ($settings->where('title', 'Product Discount')->where('options', 'yes')->count() > 0)
    <div class="form-group row">
        <label class="control-label col-md-3">Discount</label>
        <div class="col-md-8">
            <input name="discount" value="{{ old('discount') }}" onkeypress="return onlyNumber(event)" class="form-control" type="text" placeholder="Discount in amount">
        </div>
    </div>
@endif

<!--   OPENING QUANTITY  -->
<div class="form-group row add_asterisk" id="opening_qty_div">
    <label class="control-label col-md-3">Opning Quantity</label>
    <div class="col-md-8">
        <input name="opening_quantity" id="opening_qty_field" value="{{ old('opening_quantity', 0) }}" class="form-control opening-quantity" onkeypress="return onlyNumber(event)" type="text" placeholder="Opening Quantity">
        <div class="text-danger">
            {{ $errors->has('opening_quantity') ? $errors->first('opening_quantity') : '' }}
        </div>
    </div>
</div>


<!--   BARCODE  -->
<div class="form-group row">
    <label class="control-label col-md-3">Barcode</label>
    <div class="col-md-8">
        <input name="barcode" value="{{ old('barcode') }}" class="form-control" type="text" placeholder="Product Barcode">
    </div>
</div>


<!--   Warranty Days  -->
<div class="form-group row add_asterisk">
    <label class="control-label col-md-3">Warranty</label>
    <div class="col-md-8">
        <input name="warranty_days" value="{{ old('warranty_days')??0 }}" onkeypress="return onlyNumber(event)" class="form-control" type="text" placeholder="Warranty in days">
    </div>
</div>


<!--   Guarantee Days  -->
<div class="form-group row add_asterisk">
    <label class="control-label col-md-3">Guarantee</label>
    <div class="col-md-8">
        <input name="guarantee_days" value="{{ old('guarantee_days')??0 }}" onkeypress="return onlyNumber(event)" class="form-control" type="text" placeholder="Guarantee in days">
    </div>
</div>

@include('admin.product.products.includes.serial-products')
