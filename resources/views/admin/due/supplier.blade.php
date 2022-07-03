
@extends('admin.master')

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="bs-componen">
                    <div class="card">
                        <h5 class="card-header bg-primary text-white">
                            <span class="float-left"><i class="fa fa-plus"></i> Supplier Payment</span>
                            <a href="{{ route('due-collections.index', ['type' => request('type')]) }}"
                               class="btn btn-info btn-sm"
                               style="float: right; "></i>Payment List</a>
                        </h5>

                        <div class="card-body">
                            @include('partials._alert_message')

                            <form action="/due-collections?type={{ request('type') }}" method="POST">
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
                                                               class="form-control" placeholder="Search Supplier..">
                                                        <input type="hidden" name="id" id="supplier_id">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Supplier Code</label>
                                                    <div class="col-md-9">
                                                        <input type="text" readonly name="supplier_code"
                                                               value="{{ old('supplier_code') }}" class="form-control"
                                                               id="supplier_code">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Mobile Number</label>
                                                    <div class="col-md-9">
                                                        <input type="text" readonly name="phone_number"
                                                               value="{{ old('phone_number') }}" class="form-control"
                                                               id="phone_number">
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
                                                        <input type="text" name="date" id="dateField" value="{{ date('Y-m-d') }}" required class="form-control dateField" placeholder="Date">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Select Account</label>
                                                    <div class="col-md-8">
                                                        <select name="account_id" class="form-control select-account select2">
                                                            <option value="">Select One</option>
                                                            @foreach($accounts as $account)
                                                                <option value="{{$account->id}}" data-total-amount ="{{ $account->total_amount }}">{{ $account->account_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <p class="account-balance py-0 my-0"></p>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Payment Method</label>
                                                    <div class="col-md-8">
                                                        <select name="payment_method_id" class="form-control select2">
                                                            @foreach($paymentMethods as $id => $name)
                                                                <option value="{{ $id }}" {{ old('payment_method_id') == $id ? 'selected' : '' }}>{{ ucwords($name) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Discount -->
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Discount</label>
                                                    <div class="col-md-8">
                                                        <input type="text" onkeypress="return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57" name="discount" class="form-control discount" autocomplete="off" style="font-size: 17px">
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
                                                        <input type="text" name="amount" class="form-control given-amount" onkeypress="return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57" style="font-size: 17px">
                                                    </div>
                                                </div>

                                                <div class="form-group row ">
                                                    <label class="control-label col-md-3">Current Balance</label>
                                                    <div class="col-md-8" >
                                                        <input type="text" class="form-control current-amount" name="current_due" readonly style="font-size: 17px">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Note</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" name="note">
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

            $('.select-account').change(function () {
                if ($('.select-account').val() == '') {
                    $('.account-balance').text('')
                } else {
                    $('.account-balance').text($('.select-account option:selected').data('total-amount'))
                }
            })
            // prevent given amount is not greater than account balance
            $('.given-amount, .discount').keyup(function () {
                let amount = $(this).val()
                let accountBalance = $('.account-balance').text()
                if (amount > 0 && accountBalance == '') {
                    alert('Please select an account first')
                    $(this).val('')
                } else if (parseFloat(amount) > parseFloat(accountBalance)) {
                    alert('Payment amount cant be up to ' + $('.select-account option:selected').text() +  ' account balance')
                    $(this).val('')
                } else if (amount > 0) {
                    calculateCurrentBalance()
                }
            })

            $("#customer").autocomplete({
                source: "{{ route('get-supplier') }}",
                minLength: 1,
                select: function (key, value) {
                    $('#supplier_name').val(value.item.value)
                    $('#supplier_id').val(value.item.id)
                    $('#supplier_code').val(value.item.supplier_code)
                    $('#phone_number').val(value.item.phone)
                    getSupplierBalance(value.item.id)
                    // let previous_due = value.item.previous_due
                    // $('#payable_dueAmount').val(previous_due.toFixed(2))
                    // $('#advanced_payment').val((Math.abs(value.item.advanced_payment)).toFixed(2))
                    // $('form').attr('action', '/customer-due-collection/'+value.item.id)
                },
                autoFocus: true
            })
        })


        function calculateCurrentBalance()
        {
            let advance = Number($('#advanced_payment').val())
            let previous_due = Number($('#payable_dueAmount').val())
            let amount = $('.given-amount').val()
            let discount = $('.discount').val() | 0

            if (amount > 0) {
                $('.current-amount').val((previous_due - advance - amount - discount).toFixed(2))
            }
        }

        function getSupplierBalance(supplier_id) {
            if (supplier_id != undefined) {
                let baseUrl = "/get-supplier-balance/" + supplier_id
                $.ajax({
                    url: baseUrl,
                    type: 'GET',
                    success: function (res) {
                        $('#payable_dueAmount').val(res.previous_due)
                        $('#advanced_payment').val(res.advanced_payment)
                        calculateCurrentBalance()
                    }
                })
            }
        }
        function compare_due_amount() {
            var dueAmount = 0;
            var this_payable = parseFloat($('#payable_dueAmount').val());
            var this_paid = parseFloat($("#paid_due_amount").val());

            if (this_paid) {
                $('#dueAmount').val((parseFloat(this_payable) - parseFloat(this_paid)).toFixed(2))
            } else {
                $('#dueAmount').val(0)
            }

        }
        $(document).ready(function () {
            compare_due_amount()
        });

    </script>

@stop
