
<div class="row">
    <div class="col-sm-12">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th style="border: none" width="35%">
                        <a href="#" title="Add New Product" data-toggle="modal" style="cursor: pointer" data-target="#addnew" class="text-primary">Customer
                            <strong style="font-size: 16px">+</strong>
                        </a>
                    </th>
                    @if($settings->where('title', 'Warehouse Wise Product Stock')->where('options', 'yes')->count() > 0)
                        <th style="border: none" width="25%"><a href="{{ route('warehouses.create') }}" target="_blank">Warehouse</a></th>
                    @endif
                    @if($settings->where('title', 'Courier Service For Sale')->where('options', 'yes')->count() > 0)
                        <th style="border: none" width="25%"><a href="{{ route('curriers.create') }}" target="_blank">
                            Currier
                            <strong style="font-size: 16px">+</strong>
                        </a></th>
                    @endif
                    <th style="border: none" width="15%">Sale Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="hidden" class="default-customer-id" value="{{ optional($customers->where('is_default', 1)->first())->id }}">
                        <select name="fk_customer_id" required onchange="setCustomerBalance(this)" class="form-control select-customer select2 mr-0" style="width: 100%">
                            <option value="">Select</option>
                            @foreach($customers as $key => $customer)
                                <option value="{{ $customer->id }}"
                                    data-due-limit="{{ $customer->due_limit }}"
                                    data-current-balance="{{ $customer->balance }}"
                                    {{ old('fk_customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>

                    @if($settings->where('title', 'Warehouse Wise Product Stock')->where('options', 'yes')->count() > 0)
                        <td>
                            <select name="warehouse_id" class="form-control select-warehouse select2">
                                <option value="">Show Room</option>
                                @foreach($warehouses as $id => $warehouse)
                                    <option value="{{ $id }}" {{ $id == old('warehouse_id') ? 'selected' : '' }}>{{ $warehouse }}</option>
                                @endforeach
                            </select>
                        </td>
                    @else
                        <input type="hidden" name="warehouse_id" value="">
                        <input type="hidden" class="select-warehouse" value="">
                    @endif

                    @if($settings->where('title', 'Courier Service For Sale')->where('options', 'yes')->count() > 0)
                        <td>
                            <select name="currier_id" class="select-currier form-control select2">
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

        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th style="border: none" width="55%">
                        Marketers Name
                        <i class="fas fa-running"></i>
                        <i class="fa fa-male" aria-hidden="true"></i>
                    </th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select class="form-control select2 mr-0" name="marketers_id" style="width: 100%">
                            <option value="">Select</option>
                            @foreach($marketers as $key => $marketer)
                                <option value="{{ $marketer->id }}"
                                    {{-- data-due-limit="{{ $marketer->due_limit }}" --}}
                                    {{-- data-current-balance="{{ $customer->balance }}" --}}
                                    {{ old('marketers_id') == $marketer->id? 'selected' : '' }}>
                                    {{ $marketer->marketers_name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <textarea name="note" id="note" cols="55" rows="2"></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


    <br>
</div>

