
<div class="row mb-2">
    <div class="col-md-4 pr-0">
        <div class="row supplier-row">
            <div class="supplier-text-area">
                <span class="input-group-text" style="background: transparent; border: none">Supplier</span>
            </div>
            <div class="supplier-select-area" style="min-width: 250px">
                <select name="fk_supplier_id" required class="form-control select-supplier select2 mr-0" style="width: 100%">
                    <option value="">Select</option>
                    @foreach($suppliers as $id => $supplier)
                        <option value="{{ $id }}" {{ old('fk_supplier_id') == $id ? 'selected' : '' }}>{{ $supplier }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-4 pr-0">
        <div class="row supplier-row">
            <div class="">
                <span class="input-group-text" style="background: transparent; border: none">Warehouse</span>
            </div>
            <div style="min-width: 250px">
                <select name="warehouse_id" class="form-control select-warehouse select2 mr-0" style="width: 100%">
                    <option value="">Show Room</option>
                    @foreach($warehouses as $id => $warehouse)
                        <option value="{{ $id }}" {{ old('warehouse_id') == $id ? 'selected' : '' }}>{{ $warehouse }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-3 ml-auto">
        <input name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" class="form-control dateField" type="text" placeholder="Date">
    </div>

    <br>
    <br>
    <br>
</div>
