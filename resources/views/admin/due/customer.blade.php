
@extends('admin.master')

@section('title', ' - Collection')

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="bs-componen">
                    <div class="card">
                        <h5 class="card-header bg-primary text-white">
                            <span class="float-left"><h4><i class="fa fa-plus"></i> Customer Due Collection</h4></span>
                            <a href="{{ route('due-collections.index', ['type' => request('type')]) }}" class="btn
                            btn-info btn-sm" style="float: right;"> Payment List</a>
                        </h5>

                        <div class="card-body">

                            @include('partials._alert_message')

                            <form action="/due-collections?type={{ request('type') }}" method="POST">
                                @csrf
                                <div class='row'>
                                    <div class="col-12">
                                        <div class="row">

                                            <!-- Left Side -->
                                            <div class="col-sm-6">
                                                <!-- Customer Name -->
                                                <div class="form-group row">
                                                    <label class="control-label col-3"><b>Customer Name</b></label>
                                                    <div class="col-md-9">
                                                        <input type="text" id="customer" autofocus required class="form-control" placeholder="Search Customer..">
                                                        <input type="hidden" name="id" id="customer_id">
                                                    </div>
                                                </div>

                                                <!-- Customer Code -->
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Customer Code</label>
                                                    <div class="col-md-9">
                                                        <input type="text" readonly name="customer_code" value="{{ old('customer_code') }}" class="form-control" id="customer_code">
                                                    </div>
                                                </div>

                                                <!-- Customer Mobile Number -->
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Mobile Number</label>
                                                    <div class="col-md-9">
                                                        <input type="text" readonly name="phone_number" value="{{ old('phone_number') }}" class="form-control" id="phone_number">
                                                    </div>
                                                </div>

                                                <!-- Customer Current Balance -->
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Current Balance</label>
                                                    <div class="col-md-9">
                                                        <input type="text" readonly name="previous_balance" value="{{ old('previous_balance') }}" class="form-control previous-due"  style="font-size: 17px;">
                                                    </div>
                                                </div>

                                                <!-- Customer Advance Amount -->
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Advanced</label>
                                                    <div class="col-md-9">
                                                        <input type="text" readonly name="advanced_payment" value="{{ old('advanced_payment') }}" class="form-control advance-payment" style="font-size: 17px;">
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Right Side -->
                                            <div class="col-sm-6">
                                                <!-- Payment Date -->
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3 pr-0"><b>Date</b></label>
                                                    <div class="col-md-8 ">
                                                        <input type="text" name="date" id="dateField" value="{{ date('Y-m-d') }}" required class="form-control dateField" placeholder="Date">
                                                    </div>
                                                </div>

                                                <!-- Discount -->
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Discount</label>
                                                    <div class="col-md-8">
                                                        <input type="text" onkeypress="return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57" name="discount" class="form-control discount" autocomplete="off" style="font-size: 17px">
                                                    </div>
                                                </div>

                                                <!-- Given Amount -->
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Amount</label>
                                                    <div class="col-md-8">
                                                        <input type="text" onkeypress="return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57" name="amount" class="form-control given-amount" autocomplete="off" style="font-size: 17px">
                                                    </div>
                                                </div>

                                                <!-- Receivable Due Amount -->
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Receivable Due</label>
                                                    <div class="col-md-8">
                                                        <input type="text" readonly name="payable_dueAmount" value="{{ old('payable_dueAmount') }}" class="form-control receivable-due-amount" id="payable_dueAmount" style="font-size: 17px">
                                                    </div>
                                                </div>

                                                <!-- Bank Account -->
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Account</label>
                                                    <div class="col-md-8">
                                                        <select name="account_id" class="form-control select2 select-account">
                                                            @foreach($accounts as $account)
                                                                <option value="{{$account->id}}" data-total-amount ="{{ $account->total_amount }}">{{$account->account_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Payment Method -->
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
                                                <br>

                                                <!-- Action -->
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
                $('.account-balance').text($('.select-account option:selected').data('total-amount') + " Tk.")
            })
            $("#customer").autocomplete({
                source: "{{ route('get-customer') }}",
                minLength: 1,
                select: function (key, value) {
                    $('#customer_name').val(value.item.value)
                    $('#customer_id').val(value.item.id)
                    $('#customer_code').val(value.item.customer_code)
                    $('#phone_number').val(value.item.phone)
                    $('#payable_dueAmount').val(value.item.previous_due)
                    $('#advanced_payment').val(value.item.advanced_payment)
                    getCustomerBalance(value.item.id)
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


        function getCustomerBalance(customer_id) {
            let baseUrl = '{{ url('/') }}' + "/get-customer-balance/" + customer_id
            $.ajax({
                url: baseUrl,
                type: 'GET',
                success: function (res) {
                    $('.previous-due').val(res.previous_due)
                    $('.advance-payment').val(res.advanced_payment)
                    calculateReceivableDueAmount()
                }
            })
        }

        $('.given-amount, .discount').keyup(function () {
            calculateReceivableDueAmount()
        })

        function calculateReceivableDueAmount() {
            let given_amount = $('.given-amount').val()
            let discount = $('.discount').val() | 0

            $('.receivable-due-amount').val(Number($('.previous-due').val()) - Number($('.advance-payment').val()) - Number(given_amount) - Number(discount))

        }
    </script>
@stop
