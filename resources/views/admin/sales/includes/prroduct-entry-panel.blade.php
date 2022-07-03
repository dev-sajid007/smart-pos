
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="40%">Product Name</th>
                    <th width="20%" class="product-serial-section" hidden>Serial</th>
                    <th width="20%">Available Qty</th>
                    <th width="20%">Quantity</th>
                    <th width="20%">Unit Cost</th>
                </tr>
            </thead>

            <tbody>
                            {{-- @foreach($products as $key => $product)
                                @dd($product)
                            @endforeach --}}
                <tr>
                    <td style="padding-top: 2px; padding-bottom: 2px">
                        <select class="form-control select-product select2 mr-0" style="width: 100%">
                            <option value="">Select</option>
                            @foreach($products as $key => $product)
                                <option value="{{ $product->id }}"
                                        data-product-name="{{ $product->product_name }}"
                                        data-product-code="{{ $product->product_code }}"
                                        data-product-cost="{{ $product->product_price }}"
                                        data-tax-percent="{{ $product->tax }}"
                                        data-available-quantity="{{ $product->available_quantity }}"
                                        data-has-serial="{{ $product->has_serial }}"
                                    >
                                    {{ $product->product_name }} ({{ $product->product_code }}) {{ ($product->has_serial==1)? '--Serial' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="product-serial-section" hidden></td>
                    <td style="padding-top: 2px; padding-bottom: 2px">
                        <input class="select-available-quantity form-control text-danger" readonly type="text" value="">
                    </td>
                    <td style="padding-top: 2px; padding-bottom: 2px">
                        <input class="select-quantity form-control" onkeyup="checkAvailableQty()" onkeypress="return onlyNumber(event)" type="text" value="">
                    </td>
                    <td style="padding-top: 2px; padding-bottom: 2px">
                        <input class="select-unit-cost form-control" type="text" value="">
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 2px; padding-bottom: 2px">
                        <input type="text" class="form-control" onkeyup="getProductBySerial(this)" placeholder="Scan Product Serial">
                    </td>
                    <td class="product-serial-section" hidden></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
