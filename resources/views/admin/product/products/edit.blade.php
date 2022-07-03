@extends('admin.master')
@section('title', 'Product Edit')
@section('content')

    <main class="app-content">

        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <div class="tile">
                    <a class="float-right btn btn-sm btn-info" href="{{ route('products.index') }}">Back</a>
                    <h3 class="tile-title"><i class="fa fa-edit"></i> Edit Product</h3>

                    <hr>

                    <div class="tile-body">
                        <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ route('products.update', $product->id) }}">
                            @csrf @method('PUT')

                            <div class="row">
                                <div class="col-6">
                                    <!-- Supplier -->
                                    <div class="form-group row">
                                        <label class="control-label col-md-3" style="cursor: pointer" type="button">Supplier</label>
                                        <div class="col-md-9">
                                            <select name="supplier_id" class="form-control select2">
                                                <option value="" disabled selected>- Select Supplier -</option>
                                                @foreach ($suppliers as $id => $name)
                                                    <option {{  old('supplier_id', $product->supplier_id) == $id ? 'selected' : ''  }} value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>

                                            <div class="text-danger">
                                                {{ $errors->has('supplier_id') ? $errors->first('supplier_id') : '' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Name -->
                                    <div class="form-group row add_asterisk">
                                        <label class="control-label col-md-3">Product Name</label>
                                        <div class="col-md-9">
                                            <input name="product_name" value="{{ $product->product_name }}" class="form-control" type="text" placeholder="Product Name">
                                            <div class="text-danger">
                                                {{ $errors->has('product_name') ? $errors->first('product_name'):'' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Code -->
                                    <div class="form-group row add_asterisk">
                                        <label class="control-label col-md-3">Product Code</label>
                                        <div class="col-md-9">
                                            <input name="product_code" value="{{ old('product_code', $product->product_code) }}" class="form-control" type="text" placeholder="Product Code">
                                            <div class="text-danger">
                                                {{ $errors->has('product_code') ? $errors->first('product_code'):'' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Brand -->
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Brand</label>
                                        <div class="col-md-9">
                                            <select name="brand_id" id="brand_id" class="form-control select2">
                                                <option value="">Select</option>
                                                @foreach ($brands as $id => $brand)
                                                    <option {{  old('brand_id', $product->brand_id) == $id ? 'selected' : ''  }} value="{{ $id }}">{{ $brand }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <!-- Category -->
                                    <div class="form-group row add_asterisk">
                                        <label class="control-label col-md-3">Category</label>
                                        <div class="col-md-9">
                                            <select name="fk_category_id" id="fk_category_id" class="form-control select2">
                                                <option value="">Select</option>
                                                @foreach ($categories->pluck('category_name', 'id') as $id => $name)
                                                    <option {{ old('fk_category_id', $product->fk_category_id) == $id ? 'selected' : ''  }} value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger">
                                                {{ $errors->has('fk_category_id') ? $errors->first('fk_category_id'):'' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!--   subcategory  -->
                                    @if ($settings->where('title', 'Product Subcategory')->where('options', 'yes')->count() > 0)
                                        <div class="form-group row">
                                            <label class="control-label col-md-3" style="cursor: pointer">Sub Category</label>
                                            <div class="col-md-9">
                                                <select name="fk_sub_category_id" id="fk_sub_category_id" class="form-control select2">
                                                    <option value="">Select</option>
                                                    @foreach (optional($categories->where('id', (request('fk_sub_category_id') ?? $product->fk_category_id))->first())->subcategories ?? [] as $subcategory)

                                                        <option {{ old('fk_sub_category_id', $product->fk_sub_category_id) == $subcategory->id ? 'selected' : ''  }} value="{{ $subcategory->id }}">{{ $subcategory->sub_category_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($settings->where('title', 'Product Generic Name')->where('options', 'yes')->count() > 0)
                                        <div class="form-group row">
                                            <label class="control-label col-md-3">Generic</label>
                                            <div class="col-md-9">
                                                <select name="generic_id" id="generic_id" class="form-control select2">
                                                    <option value="">Select</option>
                                                    @foreach ($generics as $id => $name)
                                                        <option {{ old('generic_id', $product->generic_id) == $id ? 'selected' : ''  }} value="{{ $id }}">{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($settings->where('title', 'Product Rak In Product')->where('options', 'yes')->count() > 0)
                                        <div class="form-group row">
                                            <label class="control-label col-md-3">Product Rak No</label>
                                            <div class="col-md-9">
                                                <select name="rak_id" id="product_rak_id" class="form-control select2">
                                                    <option value="">Select</option>
                                                    @foreach ($productRaks as $id => $name)
                                                        <option {{ old('rak_id', optional($product->product_rak)->rak_id) == $id ? 'selected' : '' }} value="{{ $id }}">{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Product Unit -->
                                    <div class="form-group row add_asterisk">
                                        <label class="control-label col-md-3">Product Unit</label>
                                        <div class="col-md-9">
                                            <select name="fk_product_unit_id" id="" class="form-control select2">
                                                @foreach ($product_units as $product_unit)
                                                    <option value="{{ $product_unit->id }}" {{ old('fk_product_unit_id', $product->fk_product_unit_id) == $product_unit->id ? 'selected' : '' }}>{{ ucfirst($product_unit->name) }}</option>
                                                @endforeach
                                            </select>

                                            <div class="text-danger">
                                                {{ $errors->has('fk_product_unit_id') ? $errors->first('fk_product_unit_id'):'' }}
                                            </div>
                                        </div>
                                    </div>

                                    @if ($settings->where('title', 'Product Expire Date')->where('options', 'yes')->count() > 0)
                                        <div class="form-group row">
                                            <label class="control-label col-md-3">Expire Date</label>
                                            <div class="col-md-9">
                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                    <input type="text" name="expire_date" autocomplete="off" placeholder="dd/mm/yyyy" value="{{ old('expire_date', $product->expire_date) }}" class="form-control expire-date">
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                    <!-- Product Description -->
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Product Description</label>
                                        <div class="col-md-9">
                                            <textarea name="product_description" class="form-control" style="height: 70px" placeholder="Product Description">{{ $product->product_description }}</textarea>
                                            <div class="text-danger">
                                                {{ $errors->has('product_description') ? $errors->first('product_description'):'' }}
                                            </div>
                                        </div>
                                    </div>
{{-- 

                                    @if ($warehouses->count() > 0 && ($settings->where('title', 'Warehouse Wise Product Stock')->where('options', 'yes')->count() > 0))
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                                <th>Sl</th>
                                                <th>Name</th>
                                                <th width="150" class="text-center">Opening Stock</th>
                                            </thead>

                                            @php $warehouseStockIds = $product->warehouse_stocks()->pluck('warehouse_id')->toArray() ?? ['default'] @endphp

                                            <tbody>
                                                @foreach($warehouses as $key => $warehouse)
                                                    <tr>
                                                        <td>
                                                            <div class="animated-checkbox">
                                                                <label>
                                                                    <input type="checkbox" {{ in_array($warehouse->id, $warehouseStockIds) ? 'checked' : '' }} name="checked_warehouse_ids[]" value="{{ $warehouse->id }}" {{ old('is_common_generated') ? 'checked' : '' }} class="form-control check-all">
                                                                    <span class="label-text">{{ $key + 1 }}</span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="warehouse_ids[]" value="{{ $warehouse->id }}">
                                                            {{ $warehouse->name }}
                                                        </td>
                                                        <td class="text-center">
                                                            <input name="opening_stocks[]" onkeypress="return event.charCode >= 46 && event.charCode <= 57" style="width: 120px; margin-left: 10px" class="form-control form-control-sm text-center" value="{{ optional(optional($product->warehouse_stocks)->where('warehouse_id', $warehouse->id)->first())->opening_quantity }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif --}}
                                </div>

                                <!-- Purchase Price -->
                                <div class="col-6">

                                    <div class="form-group row add_asterisk">
                                        <label class="control-label col-md-3">Purchase Price</label>
                                        <div class="col-md-8">
                                            <input name="product_cost" value="{{ $product->product_cost }}" class="form-control" type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57" placeholder="Product Cost">
                                            <div class="text-danger">
                                                {{ $errors->has('product_cost') ? $errors->first('product_cost'):'' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sale Price -->
                                    <div class="form-group row add_asterisk">
                                        <label class="control-label col-md-3">Sales Price</label>
                                        <div class="col-md-8">
                                            <input name="product_price" value="{{ $product->product_price }}" class="form-control" type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57" placeholder="Product Price">
                                            <div class="text-danger">
                                                {{ $errors->has('product_price') ? $errors->first('product_price'):'' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!--  HOLE SALE PRICE  -->
                                    @if ($settings->where('title', 'Hole Sale Price')->where('options', 'yes')->count() > 0)
                                        <div class="form-group row ">
                                            <label class="control-label col-md-3">Hole Sale Price</label>
                                            <div class="col-md-8">
                                                <input name="holesale_price" onkeypress="return event.charCode >= 46 && event.charCode <= 57" value="{{ old('holesale_price', $product->holesale_price) }}" class="form-control" type="text" placeholder="Hole Sale Price">
                                                <div class="text-danger">
                                                    {{ $errors->has('holesale_price') ? $errors->first('holesale_price') : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Alert Quantity -->
                                    <div class="form-group row add_asterisk">
                                        <label class="control-label col-md-3">Alert Quantity</label>
                                        <div class="col-md-8">
                                            <input name="product_alert_quantity" value="{{ $product->product_alert_quantity }}" class="form-control" type="number" placeholder="Alert Quantity">
                                            <div class="text-danger">
                                                {{ $errors->has('product_alert_quantity') ? $errors->first('product_alert_quantity'):'' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reference No -->
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Reference No</label>
                                        <div class="col-md-8">
                                            <input name="product_reference" value="{{ $product->product_reference }}" class="form-control" type="text" placeholder="Reference">
                                            <div class="text-danger">
                                                {{ $errors->has('product_reference') ? $errors->first('product_reference'):'' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tax in Percentage -->
                                    @if ($settings->where('title', 'Product Tax')->where('options', 'yes')->count() > 0)
                                        <div class="form-group row">
                                            <label class="control-label col-md-3">Tax(%)</label>
                                            <div class="col-md-8">
                                                <input name="tax" value="{{ old('tax', ($product->tax > 0 ? $product->tax : '')) }}" class="form-control" type="text" placeholder="Tax">
                                            </div>
                                        </div>
                                    @endif

                                    <!--   PRODUCT DISCOUNT  -->
                                    @if ($settings->where('title', 'Product Discount')->where('options', 'yes')->count() > 0)
                                        <div class="form-group row">
                                            <label class="control-label col-md-3">Discount</label>
                                            <div class="col-md-8">
                                                <input name="discount" value="{{ old('discount', ($product->discount > 0 ? $product->discount : '')) }}" onkeypress="return event.charCode >= 46 && event.charCode <= 57" class="form-control" type="text" placeholder="Discount in amount">
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Opening Quantity -->
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Opening Quantity</label>
                                        <div class="col-md-8">
                                            <input name="opening_quantity" {{ $product->has_serial ? 'readonly' : '' }} value="{{ old('opening_quantity', optional($product->product_stock)->opening_quantity) }}" class="form-control opening-quantity" type="text" placeholder="Opening Quantity">
                                            <div class="text-danger">
                                                {{ $errors->has('opening_quantity') ? $errors->first('opening_quantity') : '' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!--   BARCODE  -->
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Barcode</label>
                                        <div class="col-md-8">
                                            <input name="barcode" value="{{ old('barcode', optional($product->barcode)->barcode_number) }}" class="form-control opening-quantity" type="text" placeholder="Product Barcode">
                                            <div class="text-danger">
                                                {{ $errors->has('barcode') ? $errors->first('barcode') : '' }}
                                            </div>
                                        </div>
                                    </div>


                                    <!--   Warranty Days  -->
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Warranty</label>
                                        <div class="col-md-8">
                                            <input name="warranty_days" value="{{ old('warranty_days', $product->warranty_days) }}" onkeypress="return onlyNumber(event)" class="form-control" type="text" placeholder="Warranty in days">
                                        </div>
                                    </div>
                                    
                                    
                                    <!--   Guarantee Days  -->
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Guarantee</label>
                                        <div class="col-md-8">
                                            <input name="guarantee_days" value="{{ old('guarantee_days', $product->guarantee_days) }}" onkeypress="return onlyNumber(event)" class="form-control" type="text" placeholder="Guarantee in days">
                                        </div>
                                    </div>

                                    <!-- Image -->
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Image</label>
                                        <div class="col-md-8">
                                            <input name="image" class="form-control" type="file">
                                            <p class="text-danger text-center">Image size 300 x 250 px</p>
                                        </div>
                                    </div>

                                    @if (file_exists($product->image))
                                        <label class="control-label col-md-3">Image</label>
                                            <div class="col-md-8">
                                                <img width="100" height="100" src="{{ asset($product->image) }}">
                                            </div>
                                        </div>
                                    @endif


                                    <!-- Action -->
                                    <div class="tile-footer">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button class="btn btn-primary pull-right" type="submit" type="submit">
                                                    <i class="fa fa-fw fa-lg fa-check-circle"></i>Update Product
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <input type="hidden" class="subcategory-url" value="{{ route('subcategories.index') }}">
@endsection

@section('footer-script')
    <script type="text/javascript" src="{{ asset('jq/select2Loads.js') }}"></script>

    <script type="text/javascript">
        // triggerSelect();

        $('.expire-date').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true,
            todayHighlight: true,
        });

        $('#fk_category_id').change(function () {

            var categoryId = $(this).val();
            if (categoryId) {

                $.ajax({
                    type: "GET",
                    url: $('.subcategory-url').val() + '/' + categoryId,
                    success: function (res) {
                        if (res) {
                            $("#fk_sub_category_id").empty();
                            $("#fk_sub_category_id").append('<option value="">Salect One</option>');
                            $(res).each(function (index, item) {
                                $("#fk_sub_category_id").append('<option value="' + item.id + '">' + item.sub_category_name + '</option>');
                            });
                        } else {
                            $("#fk_sub_category_id").empty();
                        }

                    }
                });
            } else {
                $("#fk_sub_category_id").empty();
            }
        });
        // function triggerSelect()
        // {
        //     select2Loads({
        //         selector: '#fk_category_id',
        //         url: '/settings/categories'
        //     });

        //     select2Loads({
        //         selector: '#fk_product_unit_id',
        //         url: '/settings/product_units'
        //     });


        //     let selectedCategory = new Option(
        //         '{{ $product->category->category_name }}',
        //         '{{ $product->fk_category_id }}',true, true);

        //     let selectedUnit = new Option(
        //         '{{ $product->product_unit->name }}',
        //         '{{ $product->fk_product_unit_id }}', true, true);

        //     $('#fk_category_id').append(selectedCategory).trigger('change');
        //     $('#fk_product_unit_id').append(selectedUnit).trigger('change');
        // }
    </script>
@endsection
