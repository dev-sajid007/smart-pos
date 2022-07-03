
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="40%">Product Name</th>
                    <th width="20%">Quantity</th>
                    <th width="20%">Unit Cost</th>
                    <th width="20%">Free Quantity</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>
                        <select class="form-control select-product select2 mr-0" style="width: 100%">
                            <option value="">Select</option>
                            @foreach($products as $key => $product)
                                <option value="{{ $product->id }}"
                                        data-product-name="{{ $product->product_name }}"
                                        data-product-code="{{ $product->product_code }}"
                                        data-product-cost="{{ $product->product_cost }}"
                                        data-has-serial="{{ $product->has_serial }}"
                                    >
                                    {{ $product->product_name }} ({{ $product->product_code }}) {{ ($product->has_serial==1)? '--Serial' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="select-quantity form-control" type="text" value="">
                    </td>
                    <td>
                        <input class="select-unit-cost form-control" type="text" value="">
                    </td>
                    <td>
                        <input class="select-free-quantity form-control" type="text" value="">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
