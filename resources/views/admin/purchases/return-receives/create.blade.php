@extends('admin.master')

@push('style')
    <style>
        .add-new-btn {
            border-radius: 0 0 0 0;
        }

        .add-new-product-button {
            padding: 4.5px;
            border-radius: 0px 5px 5px 0px;
            visibility: hidden;
        }

        .new-customer-btn {
            padding: 4.5px;
            border-radius: 0px 5px 5px 0px;
            margin-top: 28px;
        }

        .loading {
            display: none;
            justify-content: center;
            align-items: center;
            width: 100%;
            position: absolute;
            height: 100%;
            z-index: 11111;
            background: #ffffffc7;
        }
    </style>
@endpush

@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                @if($errors->any())
                    <div class="alert-danger">
                        <ul class="list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="tile">
                    <a href="{{ route('purchase-return-receives.index') }}" class="btn btn-primary pull-right" style="float:
                     right;"><i class="fa fa-eye"></i> View Receive List </a>
                    <h3 class="tile-title"> Purchase Return Receive</h3>
                    <hr>

                    <div class="tile-body">

                        <form class="form-horizontal" method="GET" action="{{ route('purchase-return-receives.create') }}">
                            <div class="row">
                                <!-- Supplier -->
                                <div class="col-md-4 pr-0">
                                    <div class="form-group">
                                        <label class="control-label">Purchase Return Id</label>
                                        <select name="purchase_return_id" class="form-control select2 mr-0" id="purchase_return_id">
                                            <option value="">- Select One -</option>
                                            @foreach($purchaseReturnIds as $id => $name)
                                                <option value="{{ $id }}" {{ request('purchase_return_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Date -->
                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label class="control-label">Date</label>
                                        <input name="date" value="{{ old('date', date('Y-m-d')) }}"
                                               class="form-control dateField" type="text" placeholder="Date">
                                        <div class="text-danger">
                                            {{ $errors->has('date') ? $errors->first('date') : '' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group mt-4">
                                        <button class="btn btn-info text-white"><i class="fa fa-search"></i> Load</button>
                                    </div>
                                </div>
                            </div>
                        </form>


                        <form class="form-horizontal" method="POST" action="{{ route('purchase-return-receives.store') }}">
                            <input type="hidden" name="date" value="{{ request('date') }}">
                            <input type="hidden" name="purchase_return_id" value="{{ request('purchase_return_id') ?? old('purchase_return_id') }}">
                            @csrf

                            <div class="row">
                                @if ($purchaseReturn)
                                    <div class="col-12">
                                        <table style="font-size: 14px; margin-bottom: 30px">
                                            <tr>
                                                <td>Supplier</td>
                                                <td>:</td>
                                                <td>{{ $purchaseReturn->supplier->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Invoice Id</td>
                                                <td>:</td>
                                                <td>{{ $purchaseReturn->invoice_id }}</td>
                                            </tr>
                                            <tr>
                                                <td>Return Date</td>
                                                <td>:</td>
                                                <td>{{ $purchaseReturn->date }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                @endif

                                <!-- Items Table -->
                                <div class="col-12">
                                    <table class="table table-bordered table-sm" style="border: none">
                                        <thead>
                                            <tr>
                                                <th>Sl.</th>
                                                <th>Product Code</th>
                                                <th>Product Name</th>
                                                <th>Condition</th>
                                                <th class="text-center">Return Quantity</th>
                                                <th class="text-center">Total Received</th>
                                                <th class="text-center">Returnable Qty</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-center">Available Quantity</th>
                                            </tr>
                                        </thead>

                                        @php $total_receive_qunatity = 0; @endphp
                                        <tbody class="r-group">
                                            @foreach(optional($purchaseReturn)->purchaseReturnDetails ?? [] as $key => $detail)
                                                @php
                                                    $receive_quantity = 0;
                                                    foreach ($purchaseReturn->purchase_return_receives as $items) {
                                                        foreach ($items->receive_details as $item) {
                                                            if ($item->product_id == $detail->product_id) {
                                                                $receive_quantity += $item->quantity;
                                                            }
                                                        }
                                                    }
                                                    $total_receive_qunatity += $receive_quantity;
                                                @endphp
                                                <tr class="r-row">
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $detail->product->product_code }}</td>
                                                    <td>
                                                        <input type="hidden" name="product_ids[]" value="{{ $detail->product_id }}">
                                                        {{ $detail->product->product_name }}
                                                    </td>

                                                    <td>{{ $detail->condition ? 'Good' : 'Damaged' }}
                                                    <input type="hidden" name="conditions[]" value="{{ $detail->condition }}"></td>
                                                    <td class="text-center quantity">{{ $detail->quantity }}</td>
                                                    <td class="text-center received">{{ $receive_quantity }}</td>
                                                    <td class="text-center returnable-quantity">{{ $detail->quantity - $receive_quantity }}</td>
                                                    <td width="100px">
                                                        <input value="{{ old('receive_quantity')[$key] }}" class="form-control receive-quantity text-center" name="receive_quantities[]" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                                    </td>
                                                    <td class="available-quantity text-center">{{ $detail->quantity - $receive_quantity }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                        @if (optional($purchaseReturn)->purchaseReturnDetails)
                                            <tfoot style="border: none">
                                                <tr>
                                                    <th colspan="4"><strong>Total:</strong></th>
                                                    <th class="text-center">{{ $purchaseReturn->purchaseReturnDetails->sum('quantity') }}</th>
                                                    <th class="text-center">{{ $total_receive_qunatity }}</th>
                                                    <th class="text-center">{{ $purchaseReturn->purchaseReturnDetails->sum('quantity') - $total_receive_qunatity }}</th>
                                                    <th class="text-center total-quantity"></th>
                                                    <th class="text-center total-available-quantity">{{ $purchaseReturn->purchaseReturnDetails->sum('quantity') - $total_receive_qunatity }}</th>
                                                </tr>

                                                <tr style="border: none">
                                                    <td style="border: none" colspan="7">
                                                        <div class="form-group">
                                                            <label>Comments</label>
                                                            <textarea name="comment" class="form-control" placeholder="Comments" style="height: 70px;"></textarea>
                                                        </div>
                                                    </td>
                                                    <td colspan="2" style="border: none">
                                                        <br><br>
                                                        <button class="btn btn-primary pull-right" type="submit" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Save</button>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        @endif
                                    </table>
                                    <br>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('footer-script')
{{--    <script src="{{ asset('jq/select2Loads.js') }}"></script>--}}
    <script>
        select2Loads({
            selector: '#purchase_return_id',
            url: '/purchase-returns'
        })
    </script>

    <script>
        $('.receive-quantity').keyup(function () {
            let receive_quantity = $(this).val()
            let returnable_quantity = $(this).closest('tr').find('.returnable-quantity').text()

            if (receive_quantity == '') {
                $(this).closest('tr').find('.available-quantity').text(returnable_quantity)
                return;
            }
            else if (Number(receive_quantity) > Number(returnable_quantity)) {
                $(this).closest('tr').find('.available-quantity').text(0)
                $(this).val(returnable_quantity)
                alert('Receive can not be upto available quantity')
                return;
            } else {
                $(this).closest('tr').find('.available-quantity').text(Number(returnable_quantity) - Number(receive_quantity))
                return;
            }
        })
    </script>
@endsection
