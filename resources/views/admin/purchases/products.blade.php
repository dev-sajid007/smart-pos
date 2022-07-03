@foreach ($supplier_products as $key => $supplier_product)
<tr>
    <td style="display: none">
        <input type="text" readonly tabindex="-1" name="product_ids[]"
               class="form-control item-box product-id"
               value="{{$supplier_product->id}}">
    </td>
    <td>
        {{--<input type="text" tabindex="-1" name="product_name[]" readonly id="product_name_{{$key}}"--}}
               {{--class="form-control item-box" value="{{$supplier_product->product_name}}">--}}
        <textarea name="product_name[]" tabindex="-1" class="form-control item-box product-name" readonly

        >{{$supplier_product->product_name}}</textarea>
    </td>
    <td>
        <input type="text"  name="unit_cost[]"  min="1"
               value="{{$supplier_product->product_cost}}" class="form-control unit-cost item-box">
    </td>
    <td style="display: none">
        <input type="text" tabindex="-1" name="unit_prices[]" readonly min="1"
               value="{{$supplier_product->product_price}} "
               class="dynamic_product_price form-control item-box unit-prices" onblur="">
    </td>
    <td>
        <input type="number" min="0" step="1"  name="quantities[]" value="0"
               class="form-control changesNo  qty_unit item-box quantity" autocomplete="off" placeholder="Qty"
               onkeyup="">
    </td>
    <td>
        <input type="number" min="0" step="1"  name="free_quantities[]" value="0"
               class="form-control changesNo  free_qty_unit item-box free-quantity" autocomplete="off"
               placeholder="Qty">
    </td>
    <td>
        <input type="number" tabindex="-1" name="product_sub_total[]"  readonly="readonly"
               min="0" class="form-control totalLinePrice item-box text-right" value="0" autocomplete="off"
               placeholder="Sub
               Total">
    </td>

</tr>



@endforeach