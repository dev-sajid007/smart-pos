

<!--   supplier name  -->
<div class="form-group row">
    <label class="control-label col-md-3 text-primary" style="cursor: pointer" type="button" data-toggle="modal" data-target="#addnew_supplier" title="Add New Supplier">Supplier</label>
    <div class="col-md-9">
        <select name="supplier_id" class="form-control select2">
            <option value="" disabled selected>- Select Supplier -</option>
            @foreach ($suppliers as $id => $name)
                <option {{  old('supplier_id') == $id ? 'selected' : ''  }} value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>

        <div class="text-danger">
            {{ $errors->has('supplier_id') ? $errors->first('supplier_id') : '' }}
        </div>
    </div>
</div>


<!--  product name -->
<div class="form-group row add_asterisk">
    <label class="control-label col-md-3">Product Name</label>
    <div class="col-md-9">
        <input name="product_name" value="{{ old('product_name') }}" class="form-control" type="text" placeholder="Product Name">
        @error('product_name')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>


<!--  product code  -->
<div class="form-group row">
    <label class="control-label col-md-3">Product Code</label>
    <div class="col-md-9">
        <input name="product_code" value="{{ old('product_code') }}" class="form-control" type="text" placeholder="Product Code">
        @error('product_code')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<!--   brand  -->
<div class="form-group row">
    <label class="control-label col-md-3">Brand</label>
    <div class="col-md-9">
        <select name="brand_id" id="brand_id" class="form-control select2">
            <option value="">Select</option>
            @foreach ($brands as $id => $brand)
                <option {{  old('brand_id') == $id ? 'selected' : ''  }} value="{{ $id }}">{{ $brand }}</option>
            @endforeach
        </select>

        @error('brand_id')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<!--   category  -->
<div class="form-group row add_asterisk">
    <label class="control-label col-md-3 text-primary" style="cursor: pointer" type="button" data-toggle="modal" data-target="#addnew_category" title="Add New Category">Category</label>
    <div class="col-md-9">
        <select name="fk_category_id" id="fk_category_id" class="form-control select2">
            <option value="">Select</option>
            @foreach ($categories as $category)
                <option {{ old('fk_category_id') == $category->id ? 'selected' : ''  }} value="{{ $category->id }}">{{ $category->category_name }}</option>
            @endforeach
        </select>
        @error('fk_category_id')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<!--   subcategory  -->
@if ($settings->where('title', 'Product Subcategory')->where('options', 'yes')->count() > 0)
    <div class="form-group row">
        <label class="control-label col-md-3" style="cursor: pointer">Sub Category</label>
        <div class="col-md-9">
            <select name="fk_sub_category_id" id="fk_sub_category_id" class="form-control select2">
                <option value="">Select</option>
                @foreach (optional($categories->where('id', request('fk_sub_category_id'))->first())->subcategories ?? [] as $subcategory)

                    <option {{ old('fk_sub_category_id') == $subcategory->id ? 'selected' : ''  }} value="{{ $subcategory->id }}">{{ $subcategory->sub_category_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endif

<!--   generic  -->
@if ($settings->where('title', 'Product Generic Name')->where('options', 'yes')->count() > 0)
    <div class="form-group row">
        <label class="control-label col-md-3">Generic</label>
        <div class="col-md-9">
            <select name="generic_id" id="generic_id" class="form-control select2">
                <option value="">Select</option>
                @foreach ($generics as $id => $name)
                    <option {{ old('generic_id') == $id ? 'selected' : ''  }} value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endif

<!--  product rak no  -->
@if ($settings->where('title', 'Product Rak In Product')->where('options', 'yes')->count() > 0)
    <div class="form-group row">
        <label class="control-label col-md-3">Product Rak No</label>
        <div class="col-md-9">
            <select name="rak_id" id="product_rak_id" class="form-control select2">
                <option value="">Select</option>
                @foreach ($productRaks as $id => $name)
                    <option {{ old('rak_id') == $id ? 'selected' : '' }} value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endif

<!--   product unit  -->
<div class="form-group row add_asterisk">
    <label class="control-label col-md-3">Product Unit</label>
    <div class="col-md-9">
        <select name="fk_product_unit_id" id="" class="form-control select2">
            @foreach ($product_units as $product_unit)
                <option value="{{ $product_unit->id }}" {{ old('fk_product_unit_id') == $product_unit->id ? 'selected' : '' }}>{{ ucfirst($product_unit->name) }}</option>
            @endforeach
        </select>

        <div class="text-danger">
            {{ $errors->has('fk_product_unit_id') ? $errors->first('fk_product_unit_id'):'' }}
        </div>
    </div>
</div>


<!--   expire date  -->
@if ($settings->where('title', 'Product Expire Date')->where('options', 'yes')->count() > 0)
    <div class="form-group row">
        <label class="control-label col-md-3">Expire Date</label>
        <div class="col-md-9">
            <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                </div>
                <input type="text" name="expire_date" autocomplete="off" placeholder="dd/mm/yyyy" value="{{ old('expire_date') }}" class="form-control expire-date">
            </div>
        </div>
    </div>
@endif

<!--   PRODUCT DESCRIPTION  -->
<div class="form-group row">
    <label class="control-label col-md-3">Description</label>
    <div class="col-md-9">
        <textarea name="product_description" style="height: 70px" class="form-control" placeholder="Product Description">{{ old('product_description') }}</textarea>
        <div class="text-danger">
            {{ $errors->has('product_description') ? $errors->first('product_description') : '' }}
        </div>

    </div>
</div>

<!--   PRODUCT IMAGE  -->
<div class="form-group row">
    <label class="control-label col-md-3">Image</label>
    <div class="col-md-9">
        <input name="images[]" class="form-control" type="file" multiple>
        <p class="text-danger text-center">Image size 300 x 250 px</p>
    </div>
</div>