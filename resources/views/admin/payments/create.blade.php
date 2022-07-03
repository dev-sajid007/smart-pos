@extends('admin.master')
@section('title', ' - Create Debit Voucher')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.4.5/select2.css"
          integrity="sha256-CYty2opy+S5jk6qst9frsqjryDMVw/jIZNqcIzoAxYs=" crossorigin="anonymous"/>
@endpush

@section('content')
    <main class="app-content">


        <form class="form-horizontal" id="submit-form" method="post" action="{{ route('payments.store', ['type'=>request('type')]) }}">
            @csrf

            <div class="row">
                <div class="col-md-12">

                    @include('partials._alert_message')

                    <div class="tile">
                        <div class="d-flex">
                            <h3 class="text-primary"> Add New {{ ucfirst(request('type')) }} Voucher</h3>

                            <a href="{{ route('payments.index', ['type'=>request('type')]) }}" title="Voucher List" class="btn btn-success mr-0 ml-auto" style="height: 30px; padding-top: 3px">
                                <i class="fa fa-list"></i>  List
                            </a>
                            <a href="javascript:void(0)" title="Add Party" class="btn btn-primary" data-toggle="modal" data-target="#addnew"  style="height: 30px; margin-left: 5px; padding-top: 3px">
                                <i class="fa fa-plus"></i>  Party
                            </a>
                        </div>

                        <div class="bg-primary mb-1" style="height: 5px"></div>

                        <div class="tile-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row add_asterisk">
                                                <div class="col-md-4">
                                                    <label for="select_2" class="control-label mb-0" title="Receive From"><strong>Pay to (Party)</strong></label>
                                                    <select class="form-control" id="partyId" name="fk_party_id">
                                                        <option value=""></option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4 mt-2">
                                                    <label class="mb-0"> <strong>Reference (#ID)</strong> </label>
                                                    <input type="text" class="form-control" name="voucher_reference" placeholder="Voucher Reference" value="{{ old('voucher_reference') }}">
                                                    <div class="text-danger">{{ $errors->has('voucher_reference') ? $errors->first('voucher_reference'):'' }}</div>
                                                </div>

                                                <div class="col-md-4 ml-auto mt-2">
                                                    <label for="payment_date" class="control-label mb-0"><strong>Payment Date</strong></label>
                                                    <input name="voucher_date" class="form-control dateField" type="text" value="{{ old('voucher_date') ?: date('Y-m-d') }}">
                                                    <div class="text-danger">{{ $errors->has('voucher_date') ? $errors->first('voucher-date'):'' }} </div>
                                                </div>


                                                <div class="col-md-4 mt-2">
                                                    <label class="control-label mb-0"> <strong>Select Account</strong> </label>
                                                    <select name="fk_account_id" class="form-control select2 select-account">
                                                        @foreach($accounts as $value)
                                                            <option data-total-amount ="{{ $value->total_amount }}" value="{{$value->id}}" {{ $value->id == old('fk_account_id') ? 'selected':'' }}>{{ $value->account_name}} , {{$value->account_no}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="text-danger">
                                                        {{ $errors->has('fk_account_id') ? $errors->first('fk_account_id'):'' }}
                                                    </div>
                                                </div>

                                                <div class="col-md-4 mt-2">
                                                    <label class="control-label mb-0"> <strong>Select Method</strong> </label>
                                                    <select name="fk_payment_id" class="form-control select2">
                                                        @foreach($payment_methods as $value)
                                                            <option value="{{$value->id}}" {{ $value->id == old('fk_payment_id') ? 'selected':'' }}>{{$value->method_name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="text-danger">
                                                        {{ $errors->first('fk_payment_id') ? $error->first('fk_payment_id'):'' }}
                                                    </div>
                                                </div>

                                                <div class="col-md-4 mt-2">
                                                    <label for="cheque_number" class="mb-0"> <strong>Cheque Number</strong></label>
                                                    <input name="cheque_number" class="form-control" type="text" placeholder="Cheque No" value="{{ old('cheque_number') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-sm" id="tab_logic">
                                        <thead>
                                            <tr>
                                                <th>Select Chart of Account</th>
                                                <th>Description</th>
                                                <th hidden>Payable Amount</th>
                                                <th width="20%">Paid Amount</th>
                                                <th width="3.8%">
                                                    <button type="button" class="btn btn-sm btn-success addRow pull-right" title="Add New Row">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td width="30%">
                                                    <select name="fk_account_chart_id[]" id="" class="chart_of_account_id"></select>
                                                </td>
                                                <td width="30%">
                                                    <textarea name="description[]" class="form-control descriptions" placeholder="Description"></textarea>
                                                </td>
                                                <td hidden>
                                                    <input name="payable_amount_unit[]" id="payable_amount_unit_1" class="form-control payable_amount1" type="number" placeholder="Payable" >
                                                </td>
                                                <td>
                                                    <input name="paid_amount_unit[]" id="paid_amount_unit_1" onkeyup="paidAmountCalc(this)" class="form-control paid_amount1" type="number" placeholder="Paid" onkeyup="">
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <th colspan="2"><strong>Total: </strong></th>
                                                <th>
                                                    <div class="input-group">
                                                        <input class="form-control paidamount" name="paid_amount" readonly="readonly" id="paid_amount" type="text" value="0">
                                                        <div class="input-group-append"><span class="input-group-text">TK</span></div>
                                                    </div>
                                                </th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="row">
                                        <div class="col-md-6 offset-md-1">
                                            <div class="form-group row add_asterisk" style="display: none;">
                                                <label for="account_info" class="control-label col-md-4"> Total PayableAmount </label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <input class="form-control" readonly="readonly"name="payable_amount" id="payable_amount" type="text"value="0">
                                                        <div class="input-group-append"><span class="input-group-text">.tk</span></div>
                                                    </div>
                                                </div>
                                            </div>

{{--                                            <div class="form-group row add_asterisk">--}}
{{--                                                <label for="account_info" class="control-label col-md-4"> Total Paid Amount </label>--}}
{{--                                                <div class="col-md-8">--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <input class="form-control paidamount" name="paid_amount" readonly="readonly" id="paid_amount" type="text" value="0">--}}
{{--                                                        <div class="input-group-append"><span class="input-group-text">TK</span></div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

                                            <div class="form-group row add_asterisk" style="display: none;">
                                                <label for="account_info" class="control-label col-md-4"> Total Due Amount </label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <input class="form-control" name="due_amount" id="due_amount" readonly="readonly" id="exampleInputAmount" type="text" value="0">
                                                        <div class="input-group-append"><span class="input-group-text">.tk</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-primary pull-right save-btn" onclick="$('#submit-form').submit()" type="button">
                                    <i class="fa fa-check-circle"></i> Create New
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    @include('admin.payments.add_party_modal')
@endsection

@section('footer-script')
    <script type="text/javascript" src="{{asset('jq/select2Loads.js')}}"></script>

    <script>
        select2Loads({
            selector: '#partyId',
            url: '/account/setup/parties',
        })
    </script>

    <script>
        $(() => {
            triggerChartOfAccount()
        })

        function triggerChartOfAccount() {

            select2Loads({
                selector: '.chart_of_account_id',
                url: "/account/setup/accounts-charts?type={{request('type')}}",
            })
        }

        function paidAmountCalc(element){
            $(element).parents('tr').find('.payable_amount1').val(element.value)
            totalPaid()
        }

        function totalPaid()
        {
            let amount = 0;
            let rows = $('.paid_amount1');
            $.each(rows, (i, row) => {
                let value = $(row).val();
                if (!value) value = 0;
                amount+= parseFloat(value)
            })
            $('.paidamount').val(amount)
        }
        // }
    </script>


    <script type="text/javascript">
        $('.select2').select2();

        $('.addRow').on('click', function () {
            addRow('parts_table');
            totalPaid()
        });

        var i = 1;

        function addRow(tableId) {
            i++;
            var table = document.getElementById(tableId);
            var tr = `<tr>
                <td>
                    <select class="form-control chart_of_account_id" name="fk_account_chart_id[]" >
                    </select>
                </td>
                <td>
                    <textarea  name="description[]" id="costPerUnit" class="form-control
                     descriptions" placeholder="Description""></textarea>
                </td>
                <td hidden>
                    <input type="number" min="0" step="any" name="payable_amount_unit[]" id="payable_amount_unit_${i}"
                                class="form-control payable_amount1 changesNo" autocomplete="off" placeholder="Payable" onkeyup="">
                </td>
                <td>
                    <input type="number" min="0" step="any" name="paid_amount_unit[]" id="paid_amount_unit_${i}"
                                onkeyup="paidAmountCalc(this)"
                                class="form-control paid_amount1"  placeholder="Paid">
                </td>
                <td>
                    <button class="btn btn-danger btn-sm remove delete" name="btn" type="button">
                        <span class="fa fa-trash-o"></span>
                    </button>
                </td>
                </tr>`;
            $('tbody').append(tr);
            triggerChartOfAccount();
        };

        $('tbody').on('click', '.remove', function () {
            $(this).parent().parent().remove();
            totalPaid()
        });


        $(document).ready(function () {

            $('form').on('focus', 'input[type=number]', function (e) {
                $(this).on('mousewheel.disableScroll', function (e) {
                    e.preventDefault()
                })
            });

            // Restore scroll on number inputs.
            $('form').on('blur', 'input[type=number]', function (e) {
                $(this).off('wheel');
            });

            // Disable up and down keys.
            $('form').on('keydown', 'input[type=number]', function (e) {
                if (e.which == 38 || e.which == 40)
                    e.preventDefault();
            });

        });
    </script>
@endsection
