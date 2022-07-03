


<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th width="8%" class="text-center">Sl</th>
            <th>Name</th>
            <th width="30%" class="text-center">Opening Stock</th>
        </tr>
    </thead>

    <tbody>
        @foreach($warehouses as $key => $warehouse)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>
                    <input type="hidden" name="warehouse_ids[]" value="{{ $warehouse->id }}">
                    {{ $warehouse->name }}
                </td>
                <td class="text-center">
                    <input name="opening_stocks[]" onkeypress="return event.charCode >= 46 && event.charCode <= 57" class="form-control text-center form-control-sm" value="{{ old('opening_stocks')[$key] }}">
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
