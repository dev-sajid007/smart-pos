
<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th style="border: none" width="35%"><a href="#" title="Add New Product" data-toggle="modal" style="cursor: pointer" data-target="#addnew" class="text-primary">Customer</a></th>
            
            @if($settings->where('title', 'Courier Service For Sale')->where('options', 'yes')->count() > 0)
                <th style="border: none" width="25%"><a href="{{ route('curriers.create') }}" target="_blank">Currier</a></th>
            @endif
            <th style="border: none" width="15%">Sale Date</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td>
                <select class="form-control select-customer select2 mr-0" onchange="selectCustomer()" name="fk_customer_id" style="width: 100%">
                    <option value="">Select</option>
                    @foreach($customers as $key => $customer)
                        <option value="{{ $customer->id }}" {{ $customer->default ? 'selected' : '' }} data-balance="{{ $customer->balance }}" data-due-limit="{{ $customer->due_limit }}">
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </td>


            @if($settings->where('title', 'Courier Service For Sale')->where('options', 'yes')->count() > 0)
                <td>

                    <select name="currier_id" class="select-currier form-control select2" onchange="selectCourier(this)">
                        <option value="" >Select Courier</option>
                        @foreach($couriers as $id => $name)
                            <option value="{{ $id }}" {{ old('currier_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </td>
            @endif
            <td>
                <input name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}" class="form-control dateField" type="text" placeholder="Date">
            </td>
        </tr>
    </tbody>
</table>

<input class="default-customer-id" type="hidden" value="{{ optional($customers->where('default', 1)->first())->id }}">