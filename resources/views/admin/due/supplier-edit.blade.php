@extends('admin.master')
@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="bs-componen">
                    <div class="card">
                        <h5 class="card-header bg-primary text-white">
                            <span class="float-left">Supplier Payment</span>
                            {{--<span class="float-right" id="changeAmount"></span>--}}
                            <a href="{{route('due-collections.index', ['type' => request('type')])}}"
                               class="btn btn-info btn-sm"
                               style="float: right; "></i>Review Amounts</a>
                        </h5>
                        <div class="card-body">
                            @if($errors->any())
                                <div class="alert-danger">
                                    <ul class="list-unstyled">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="/due-collections?type={{request('type')}}" method="POST">
                                @csrf
                                <div class='row'>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group row">
                                                    <label class="control-label col-3"><b>Supplier Name</b></label>
                                                    <div class="col-md-9">
                                                        <input type="text" id="customer" autofocus
                                                               required
                                                                class="form-control" placeholder="Search Supplier.." value="{{ $dueCollection->consumer->name ?? '' }}">
                                                        <input type="hidden" name="id" id="supplier_id">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Supplier Code</label>
                                                    <div class="col-md-9">
                                                        <input type="text" readonly name="supplier_code"
                                                               value="{{ old('supplier_code') }}" class="form-control"
                                                                id="supplier_code" value="{{ $dueCollection->consumer }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Mobile Number</label>
                                                    <div class="col-md-9">
                                                        <input type="text" readonly name="phone_number"
                                                               value="{{ old('phone_number') }}" class="form-control"
                                                                id="phone_number" value="{{ $dueCollection->consumer->phone ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Advanced</label>
                                                    <div class="col-md-9">
                                                        <input type="text" readonly name="advanced_payment"
                                                               value="{{ old('advanced_payment') }}"
                                                               class="form-control" id="advanced_payment"
                                                               style="font-size: 17px">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Previous Due</label>
                                                    <div class="col-md-9">
                                                        <input type="text" readonly name="payable_dueAmount"
                                                               value="{{ old('payable_dueAmount') }}"
                                                               class="form-control" id="payable_dueAmount"
                                                               style="font-size: 17px">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3"><b>Date</b></label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="date" id="dateField" value="{{date('Y-m-d')}}"
                                                               required
                                                               class="form-control dateField" placeholder="Date">
                                                    </div>
                                                </div>

                                                <div class="form-group row ">
                                                    <label class="control-label col-md-3">Amount</label>
                                                    <div class="col-md-8" >
                                                        <div class="animated-radio-button " style="display: none">
                                                            <label>
                                                                <input type="radio" name="payment_type" checked
                                                                       value="advance">
                                                                <span class="label-text">Advance Payment </span>
                                                            </label>
                                                        </div>
                                                        <input type="text" name="amount"
                                                               class="form-control" style="font-size: 17px"
                                                                id="" value="{{ $dueCollection->amount }}">
                                                    </div>
                                                </div>
                                                {{-- {{ $account->id == $dueCollection->account_id ? 'selected':'' }} --}}
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Select Account</label>
                                                    <div class="col-md-8">
                                                        <select name="account_id" class="form-control select2">
                                                            <option value="">Select One</option>
                                                            @foreach($accounts as $account)
                                                                <option value="{{$account->id}}" {{ $account->id == $dueCollection->account_id ? 'selected':'' }}>{{$account->account_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="control-label col-md-2">Payment Method</label>
                                                    <div class="col-md-9">
                                                        <select name="payment_method_id" class="form-control select2">
                                                            @foreach($paymentMethods as $id => $name)
                                                                <option value="{{ $id }}" {{ old('payment_method_id', $dueCollection->payment_method_id) == $id ? 'selected' : '' }}>{{ ucwords($name) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Status</label>
                                                    <div class="col-md-8">
                                                        <select name="status" class="form-control select2">
                                                            <option value="0" {{ $dueCollection->status == 0 ? 'selected':'' }}>Pending</option>
                                                            <option value="1" {{ $dueCollection->status == 1 ? 'selected':'' }}>Approved</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <br>
                                                <hr>
                                                <div class="form-group row">
                                                    <div class="col-md-8 offset-md-3">
                                                        <button class="btn btn-primary pull-right" type="submit">
                                                            <i class="fa fa-fw fa-lg fa-check-circle"></i>Submit
                                                        </button>
                                                    </div>
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
        </div>
    </main>
@endsection
@section('footer-script')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        jQuery(function ($) {
            $("#customer").autocomplete({
                source: "{{ route('get-supplier') }}",
                minLength: 1,
                select: function (key, value) {
                    $('#supplier_name').val(value.item.value)
                    $('#supplier_id').val(value.item.id)
                    $('#supplier_code').val(value.item.supplier_code)
                    $('#phone_number').val(value.item.phone)
                    $('#payable_dueAmount').val(value.item.previous_due)
                    $('#advanced_payment').val(Math.abs(value.item.advanced_payment))
                    // $('form').attr('action', '/customer-due-collection/'+value.item.id)
                },
                autoFocus: true
            })
        })
        function compare_due_amount() {
            var dueAmount = 0;
            var this_payable = parseFloat($('#payable_dueAmount').val());
            var this_paid = parseFloat($("#paid_due_amount").val());

            if (this_paid) {
                $('#dueAmount').val(parseFloat(this_payable) - parseFloat(this_paid))
            } else {
                $('#dueAmount').val(0)
            }

        }
        $(document).ready(function () {
            compare_due_amount()
        });

    </script>

@stop
