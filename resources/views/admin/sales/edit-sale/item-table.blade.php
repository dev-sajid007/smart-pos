
<table class="table table-sm table-bordered" id="table">
    <thead>
        <tr>
            <th width="3%">Sl.</th>
            <th>Product Name</th>
            <th style="display: none">Product ID</th>
            @if($has_additional_item_field)
                <th style="width: 10%;">Item Id</th>
            @endif
            <th style="width: 10%;" class="text-center">Quantity</th>
            <th width="12%">Price</th>
            @if($has_product_wise_discount)
                <th style="width: 10%;">Discount</th>
            @endif
            <th width="10%" class="text-center">Stock</th>
            <th width="12%">Subtotal</th>
            <th width="1%"></th>
        </tr>
    </thead>


    <tbody class="item-table-body">
        @if (old('product_names') == true)
            @foreach(old('product_names') as $key => $value)

                <tr>
                    <td><span class="item-serial-counter">{{ $key + 1 }}</span></td>
                    <td>
                        <input type="text" name="product_names[]" class="form-control product-name" autocomplete="off" value="{{ old('product_names')[$key] }}">
                        <input type="hidden" class="product-cost" name="product_costs[]" value="{{ old('product_names')[$key] }}">
                        <input type="hidden" class="product-id" name="product_ids[]" value="{{ old('product_ids')[$key] }}">
                        <input type="hidden" class="profit-column-input" name="profit_columns[]" value="{{ old('profit_columns')[$key] }}">

                        <p class="profit-column" >{{ old('profit_columns')[$key] }}</p>

                        <p class="product-rak-text" style="color: #156984; margin-top: -14px; margin-bottom: 0; font-size: 11px">{{ old('profit_columns')[$key] }}</p>
                        <input class="product-rak-input" type="hidden" name="product_rak_names[]" value="{{ old('profit_columns')[$key] }}">

                        <input type="hidden" name="product_taxes[]" class="tax-percent" value="{{ old('product_taxes')[$key] }}">
                    </td>
                    @if($has_additional_item_field)
                        <td><input type="text" name="product_item_ids[]" value="{{ old('product_item_ids')[$key] }}" class="form-control" placeholder="123"></td>
                    @endif
                    <td>
                        <input type="text" name="quantities[]" value="{{ old('quantities')[$key] }}" class="form-control text-center item-quantity changesNo" autocomplete="off" onkeyup="itemQuantityKeyUp(this)" onkeypress="return IsNumeric(event);">
                    </td>
                    <td>
                        <input type="text" name="unit_prices[]" value="{{ old('unit_prices')[$key] }}" class="form-control unit-price changesNo" autocomplete="off" onkeyup="priceEnter(event, this)" onkeypress="return IsNumeric(event);">
                    </td>

                    @if($has_product_wise_discount)
                        <td>
                            <input type="text" name="product_discounts[]" value="{{ old('product_discounts')[$key] }}" class="form-control product-discount changesNo" autocomplete="off" onkeyup="priceEnter(event, this)" onkeypress="return IsNumeric(event);">
                        </td>
                    @else
                        <td style="display: none">
                            <input type="text" name="product_discounts[]" class="product-discount">
                        </td>
                    @endif
                    <td>
                        <input type="hidden" name="old_stock[]" class="old-available-stock" value="{{ old('old_stock')[$key] }}">
                        <input type="text" name="stock_available[]" value="{{ old('stock_available')[$key] }}" readonly class="form-control text-center text-danger available-stock">
                    </td>

                    <td><input type="text" readonly name="product_sub_total[]"value="{{ old('product_sub_total')[$key] }}" class="form-control subtotal"></td>

                    <td>
                        <button type="button" class="btn btn-danger btn-sm" title="Delete This Row" onclick="removeRow(this)"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            @endforeach
        @else
            @foreach($sale->sale_details as $key => $detail)
                <tr>
                    <td><span class="item-serial-counter">{{ $key + 1 }}</span></td>
                    <td>
                        <input type="text" name="product_names[]" class="form-control product-name" autocomplete="off" value="{{ optional($detail->product)->product_name }}">
                        <input type="hidden" class="product-cost" name="product_costs[]" {{ $detail->product_cost }}>
                        <input type="hidden" class="product-id" name="product_ids[]" value="{{ $detail->fk_product_id }}">
                        <input type="hidden" class="profit-column-input" name="profit_columns[]" value="{{ ($detail->unit_price - $detail->product_cost) * $detail->quantity }}">

                        <p class="profit-column">{{ ($detail->unit_price - $detail->product_cost) * $detail->quantity }}</p>

                        <p class="product-rak-text" style="color: #156984; margin-top: -14px; margin-bottom: 0; font-size: 11px">{{ optional(optional(optional($detail->product)->product_rak)->rak)->name }}</p>
                        <input class="product-rak-input" type="hidden" name="product_rak_names[]" value="{{ optional(optional(optional($detail->product)->product_rak)->rak)->name }}">

                        <input type="hidden" name="product_taxes[]" class="tax-percent" value="{{ optional($detail->product)->tax ?? 0 }}">
                    </td>
                    @if($has_additional_item_field)
                        <td>
                            <input class="form-control" placeholder="id1, id2, id3...." value="{{ $detail->product_item_ids }}" name="product_item_ids[]">
                        </td>
                    @endif
                    <td>
                        <input type="text" name="quantities[]" class="form-control text-center item-quantity changesNo" value="{{ $detail->quantity }}" autocomplete="off" onkeyup="itemQuantityKeyUp(this)" onkeypress="return IsNumeric(event);">
                    </td>
                    <td>
                        <input type="text" name="unit_prices[]" class="form-control unit-price changesNo" value="{{ $detail->unit_price }}" autocomplete="off" onkeyup="priceEnter(event, this)" onkeypress="return IsNumeric(event);">
                    </td>
                    @if($has_product_wise_discount)
                        <td>
                            <input type="text" name="product_discounts[]" class="form-control product-discount changesNo" value="{{ $detail->product_discount }}" autocomplete="off" onkeyup="priceEnter(event, this)" onkeypress="return IsNumeric(event);">
                        </td>
                    @else
                        <td style="display: none">
                            <input type="text" name="product_discounts[]" class="product-discount">
                        </td>
                    @endif
                    <td>
                        <input type="hidden" name="old_stock[]" class="old-available-stock" value="{{ $detail->quantity }}">
                        <input type="text" name="stock_available[]" readonly class="form-control text-center text-danger available-stock" value="{{ optional(optional($detail->product)->warehouse_stocks->where('warehouse_id', $sale->warehouse_id)->first())->available_quantity ?? 0 }}">
                    </td>
                    <td><input type="text" readonly name="product_sub_total[]" value="{{ $detail->product_sub_total }}" class="form-control subtotal"></td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" title="Delete This Row" onclick="removeRow(this)"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
