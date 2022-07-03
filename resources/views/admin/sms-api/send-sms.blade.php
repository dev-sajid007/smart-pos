@extends('admin.master')
@section('title', ' - Send Sms')
@section('content')
    <style>
        .hideSystem{
            display: none;
        }
        .viewSystem{
            display: unset;
        }

    </style>
    <main class="app-content">
        <div class="app-title">
            <div class="div">
                <h1><i class="fa fa-laptop"></i> Sms Send</h1>
                <p>Send SMS Form</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="#">Sms Send</a></li>
            </ul>
        </div>
        <!-- Navs-->
        <div class="tile mb-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header">
                        <h2 class="mb-3 line-head" id="navs">Send SMS</h2>
                    </div>
                </div>
            </div>

            <div class="alert-message"></div>

            <div class="row" style="margin-bottom: 2rem;">
                <div class="col-lg-12">
                    <div class="bs-component">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#customers">Customers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#suppliers">Suppliers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#custom_numbers">Custom Numbers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#groups">Group </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <!-- sms send for customer number -->
                            <div class="tab-pane fade active show" id="customers">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="tile" style="margin-top: 15px;">
                                            <h3 class="tile-title">Send SMS To Customer </h3>
                                            <div class="tile-body" style="min-height: 440px">
                                                <div class="form-group row add_asterisk">
                                                    <label class="control-label col-md-3 text-right">Message</label>
                                                    <div class="col-md-8">
                                                        <textarea name="message" style="height: 70px" class="form-control customer-message-area" rows="5" type="text" placeholder="Message">{{ old('message') }}</textarea>
                                                        <span id="chars">
                                                            <b class="smsCount"></b> SMS (<b class="smsLength"></b>) Characters left
                                                        </span>
                                                        <div class="text-danger">
                                                            {{ $errors->has('message') ? $errors->first('message'):'' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="nav nav-tabs">
                                                    <li class="nav-item">
                                                        <a class="nav-link one" data-toggle="tab" onclick="chooseSystem(2)" href="#">System 2</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link two active" data-toggle="tab" onclick="chooseSystem(1)" href="#">System 1</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-pane hideSystem" id="SystemTwo">
                                                    <div style="border-bottom: 1px solid #ddd;padding: 0px 0px 20px 0;margin-bottom: 10px;">
                                                        <div class="row">
                                                            <div class="col-md-11">
                                                                <div class="float-right">
                                                                    <button class="btn btn-primary pull-right send-sms-to-customer" onclick="sendSmsToCustomers()" type="button">
                                                                        <i class="fa fa-fw fa-lg fa-check-circle"></i> Send Customer SMS
                                                                    </button>
                                                                </div>
                                                                <div class="animated-checkbox float-right" style="margin-right: 20px">
                                                                    <label>
                                                                        <input type="checkbox" class="customerCheckboxAll" checked="checked">
                                                                        <span class="label-text"> Check All</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1"></div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row add_asterisk">
                                                        <label class="control-label col-md-3 text-right">Customers</label>
                                                        <div class="col-md-8 table-wrapper-scroll-y">
                                                            <table class="table table-hover no-spacing" id="smsTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th>Name</th>
                                                                        <th>Phone</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach ($customers as $customer)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="animated-checkbox">
                                                                                <label>
                                                                                    <input type="checkbox" class="customerCheckbox" data-customer-mobile="{{ $customer->phone }}" name="customers[]" value="{{ $customer->id }}" checked="checked">
                                                                                    <span class="label-text"> </span>
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                        <td class="align-top">
                                                                            {{ $customer->name }}
                                                                        </td>
                                                                        <td class="align-top">
                                                                            {{ $customer->phone }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                @endforeach
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tile-footer">
                                                        <div class="row">
                                                            <div class="col-md-7">
                                                                <div style="float: right">
                                                                    {{ $customers->links() }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="float-right">
                                                                    <button class="btn btn-primary pull-right send-sms-to-customer" onclick="sendSmsToCustomers()" type="button">
                                                                        <i class="fa fa-fw fa-lg fa-check-circle"></i> Send Customer SMS
                                                                    </button>
                                                                </div>
                                                                <div class="animated-checkbox float-right" style="margin-right: 20px">
                                                                    <label>
                                                                        <input type="checkbox" class="customerCheckboxAll" checked="checked">
                                                                        <span class="label-text"> Check All</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="SystemOne">
                                                    <div class="row">
                                                        <div class="col-md-2 my-3">
                                                            <p class="text-right">All Customer</p>
                                                        </div>
                                                        <div class="col-md-2 my-3">
                                                            <input type="checkbox" class="animated-checkbox" id="all_customers_check" style="margin-top: 5px;height: 16px;width: 16px;">
                                                        </div>
                                                        <div class="col-md-7">
                                                            <div class="float-right">
                                                                <button class="btn btn-primary pull-right send-sms-to-customer" onclick="sendSmsToCustomers()" type="button">
                                                                    <i class="fa fa-fw fa-lg fa-check-circle"></i> Send Customer SMS
                                                                </button>
                                                            </div>

                                                        </div><div class="col-md-1 my-2"></div>
                                                        {{--
                                                            <div class="col-md-2 my-3">
                                                                <p class="text-right">Specific Customer</p>
                                                            </div>
                                                            <div class="col-md-4 my-3">
                                                                <input class="form-control" type="text" style="margin-top: 5px;">
                                                            </div><div class="col-md-6"></div> --}}
                                                        <div class="col-md-2">
                                                            <input type="hidden" value="@foreach ($customers as $customer){{ $customer->phone }}, @endforeach" id="all_customers_number">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <textarea  class="form" name="" id="set_customer_mobile" cols="51" rows="10"></textarea>
                                                        </div><div class="col-md-6"></div>
                                                        <div class="col-md-2"></div>
                                                        <div class="col-md-4 my-2">
                                                            <div class="float-right">
                                                                <div style="float: right">
                                                                    {{ $customers->links() }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- sms send for supplier number -->
                            <div class="tab-pane fade" id="suppliers">
                                <div class="tile" style="margin-top: 15px;">
                                    <h3 class="tile-title">Send SMS To Supplier </h3>
                                    <div class="tile-body" style="min-height: 440px">

                                        <div class="form-group row add_asterisk">
                                            <label class="control-label col-md-3">Message</label>
                                            <div class="col-md-8">
                                                <textarea name="message" class="form-control supplier-message-area" style="height: 70px" rows="5" type="text" placeholder="Type your message ......">{{ old('message') }}</textarea>
                                                <span id="chars"> <b class="smsCount"></b> SMS (<b class="smsLength"></b>) Characters left</span>
                                                <div class="text-danger">
                                                    {{ $errors->has('message') ? $errors->first('message'):'' }}
                                                </div>
                                            </div>
                                        </div>

                                        <ul class="nav nav-tabs">
                                            <li class="nav-item">
                                                <a class="nav-link one" data-toggle="tab" onclick="chooseSystem(3)" href="#">System 2</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link two active" data-toggle="tab" onclick="chooseSystem(4)" href="#">System 1</a>
                                            </li>
                                        </ul>
                                        <div class="tab-pane" id="supplierSystemTwo">
                                            <div style="border-bottom: 1px solid #ddd;padding: 0px 0px 20px 0;margin-bottom: 10px;">
                                                <div class="row">
                                                    <div class="col-md-11">
                                                        <div class="float-right">
                                                            <button class="btn btn-primary pull-right send-sms-to-supplier" onclick="sendSmsToSuppliers()" type="button" type="button">
                                                                <i class="fa fa-fw fa-lg fa-check-circle"></i> Send Supplier SMS
                                                            </button>
                                                        </div>
                                                        <div class="animated-checkbox float-right" style="margin-right: 20px">
                                                            <label>
                                                                <input type="checkbox" class="supplierCheckboxAll" checked="checked">
                                                                <span class="label-text"> Check All</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row add_asterisk">
                                                <label class="control-label col-md-3 text-right">Suppliers</label>
                                                <div class="col-md-8 table-wrapper-scroll-y">
                                                    <table class="table table-hover no-spacing" id="smsTable2">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Name</th>
                                                                <th>Phone</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach ($suppliers as $supplier)
                                                            <tr>
                                                                <td>
                                                                    <div class="animated-checkbox">
                                                                        <label>
                                                                            <input type="checkbox" class="supplierCheckbox" data-supplier-mobile="{{ $supplier->phone }}" name="suppliers[]" value="{{ $supplier->id }}" checked="checked">
                                                                            <span class="label-text"> </span>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td class="align-top">
                                                                    {{ $supplier->name }}
                                                                </td>
                                                                <td class="align-top">
                                                                    {{ $supplier->phone }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tile-footer">
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div style="float: right">
                                                            {{ $suppliers->links() }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="float-right">
                                                            <button class="btn btn-primary pull-right send-sms-to-supplier" onclick="sendSmsToSuppliers()" type="button" type="button">
                                                                <i class="fa fa-fw fa-lg fa-check-circle"></i> Send Supplier SMS
                                                            </button>
                                                        </div>
                                                        <div class="animated-checkbox float-right" style="margin-right: 20px">
                                                            <label>
                                                                <input type="checkbox" class="supplierCheckboxAll" checked="checked">
                                                                <span class="label-text"> Check All</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="supplierSystemOne">
                                            <div class="row">
                                                <div class="col-md-2 my-3">
                                                    <p class="text-right">All Suppliers</p>
                                                </div>
                                                <div class="col-md-2 my-3">
                                                    <input type="checkbox" class="animated-checkbox" id="all_suppliers_check" style="margin-top: 5px;height: 16px;width: 16px;">
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="float-right">
                                                        <button class="btn btn-primary pull-right send-sms-to-suppliers" onclick="sendSmsTosuppliers()" type="button">
                                                            <i class="fa fa-fw fa-lg fa-check-circle"></i> Send suppliers SMS
                                                        </button>
                                                    </div>
                                                </div><div class="col-md-1 my-2"></div>
                                                <div class="col-md-2"><input type="hidden" value="@foreach ($suppliers as $supplier){{ $supplier->phone }}, @endforeach" id="all_suppliers_number"></div>
                                                {{--
                                                    <div class="col-md-2 my-3">
                                                        <p class="text-right">Specific Customer</p>
                                                    </div>
                                                    <div class="col-md-4 my-3">
                                                        <input class="form-control" type="text" style="margin-top: 5px;">
                                                    </div><div class="col-md-6"></div>
                                                --}}
                                                <div class="col-md-4">
                                                    <textarea  class="form" name="" id="set_suppliers_mobile" cols="51" rows="10"></textarea>
                                                </div><div class="col-md-6"></div>
                                                <div class="col-md-2"></div>

                                                <div class="col-md-4 my-2">
                                                    <div style="float: right">
                                                        {{ $suppliers->links() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- sms send for custom number -->
                            <div class="tab-pane fade" id="custom_numbers">
                                <div class="tile" style="margin-top: 15px;">
                                    <h3 class="tile-title">Send SMS to custom numbers </h3>
                                    <div class="tile-body" style="min-height: 440px">
                                        <form class="form-horizontal" method="POST" action="">
                                            @csrf
                                            <div class="form-group row add_asterisk">
                                                <label class="control-label col-md-3">Mobile Numbers</label>
                                                <div class="col-md-8">
                                                    <textarea name="message number-area" style="height: 70px" class="form-control number-area" rows="5" type="text" placeholder="8801xxxxxxxxx, 8801xxxxxxxxx,.....">{{ old('message') }}</textarea>
                                                    <div class="text-danger">{{ $errors->has('sent_to') ? $errors->first('sent_to'):'' }}</div>
                                                </div>
                                            </div>
                                            <div class="form-group row add_asterisk">
                                                <label class="control-label col-md-3">Message</label>
                                                <div class="col-md-8">
                                                    <textarea name="message" style="height: 70px" class="form-control custom-message-area" rows="5" type="text" placeholder="Description">{{ old('message') }}</textarea>
                                                    <span>
                                                        <b class="smsCount"></b> SMS (<b class="smsLength"></b>) Characters left
                                                    </span>
                                                    <div class="text-danger">
                                                        {{ $errors->has('message') ? $errors->first('message'):'' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tile-footer">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button class="btn btn-primary pull-right send-single-message" onclick="sendSmsToCustomNumbers()" type="button" type="submit">
                                                            <i class="fa fa-fw fa-lg fa-check-circle"></i>Send SMS
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- sms send for custom number -->
                            <div class="tab-pane fade" id="groups">
                                <div class="tile" style="margin-top: 15px;">
                                    <h3 class="tile-title">Send SMS to custom numbers </h3>
                                    <div class="tile-body">
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="5"></th>
                                                    <th width="5">SL</th>
                                                    <th>Name</th>
                                                    <th>Numbers Count</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($smsGroups as $key=>$smsGroup)
                                                <tr>
                                                    <td width="5">
                                                        <input type="checkbox"
                                                        class="animated-checkbox all_smsGroup_check"
                                                        style="margin-top: 5px;height: 16px;width: 16px;">
                                                    </td>
                                                    <td width="5">{{ $key+1 }}</td>
                                                    <td>{{ $smsGroup->group_name }}</td>
                                                    <td class="all_smsGroup_checkk" style="display: none">{{ $smsGroup->group_mobiles }}</td>
                                                    @php
                                                        $group_mobiles = $smsGroup->group_mobiles;
                                                        $explore_array = explode(",", $group_mobiles);
                                                        $count_mobiles = count($explore_array);
                                                    @endphp
                                                    <td>{{ $count_mobiles }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tile-body" style="min-height: 440px">
                                        <form class="form-horizontal" method="POST" action="">
                                            @csrf
                                            <div class="form-group row add_asterisk">
                                                <label class="control-label col-md-3">Mobile Numbers</label>
                                                <div class="col-md-8">
                                                    <textarea name="number-area" style="height: 70px" class="form-control number-area" id="number_area_grp" rows="5" type="text" placeholder="8801xxxxxxxxx, 8801xxxxxxxxx,.....">{{ old('message') }}</textarea>
                                                    <div class="text-danger">{{ $errors->has('sent_to') ? $errors->first('sent_to'):'' }}</div>
                                                </div>
                                            </div>
                                            <div class="form-group row add_asterisk">
                                                <label class="control-label col-md-3">Message</label>
                                                <div class="col-md-8">
                                                    <textarea name="message" style="height: 70px" class="form-control custom-message-area2" rows="5" type="text" placeholder="Description">{{ old('message') }}</textarea>
                                                    <span>
                                                        <b class="smsCount"></b> SMS (<b class="smsLength"></b>) Characters left
                                                    </span>
                                                    <div class="text-danger">
                                                        {{ $errors->has('message') ? $errors->first('message'):'' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tile-footer">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button class="btn btn-primary pull-right send-single-message" onclick="sendSmsToCustomNumbers()" type="button" type="submit">
                                                            <i class="fa fa-fw fa-lg fa-check-circle"></i>Send SMS
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" class="available-balance-url" value="{{ route('get.available.sms.balance') }}">
        <input type="hidden" class="update-sms-balance-url" value="{{ route('update.sms.balance') }}">
        <input type="hidden" class="ajax-sms-route" value="{{ route('ajax.sms.send') }}">
        <input type="hidden" class="ajax-send-sms-route" value="{{ route('ajax.send.sms') }}">
    </main>
@endsection

@section('footer-script')

<script src="{{ asset('assets/custom_js/send-custom-sms.js') }}?{{ fdate(now(), 'Y-m-d h:m') }}"></script>
<script>
    $('#smsTable').DataTable({
        "bPaginate": false,
        "searching": true,
        order: []
    });
    $('#smsTable2').DataTable({
        "bPaginate": false,
        "searching": true,
        order: []
    });

    // var all_customer_mobile = $('#all_mobile').val();
    // var all_supplier_mobile = $('#supplier_all_mobile').val();
    // $('.customers, .suppliers, .groups').on('change', function() {
    //     var sender_mobile = $('#sender_mobile').val();
    //     mobileUpdate(this);
    // })
    // $('#all_customers').click(function() {
    //     getmobile(all_customer_mobile, this);
    // });
    // $('#all_supplier').click(function() {
    //     getmobile(all_supplier_mobile, this);
    // });
    // $('#group-tab').click(function() {
    //     $('#sender_mobile').text('');
    // });


    window.onload = function() {
        document.getElementById('SystemTwo').className = 'hideSystem';
        document.getElementById('supplierSystemTwo').className = 'hideSystem';
    };
    function chooseSystem(id){
        if(id==1){
            // alert('System One Selected');
            $("#SystemTwo").removeClass('hideSystem');
            $("#SystemOne").addClass('hideSystem');
        }
        else if(id==2){
            // alert('System One Selected');
            $("#SystemOne").removeClass('hideSystem');
            $("#SystemTwo").addClass('hideSystem');
        }
        else if(id==3){
            // alert('System One Selected');
            $("#supplierSystemTwo").removeClass('hideSystem');
            $("#supplierSystemOne").addClass('hideSystem');
        }
        else if(id==4){
            // alert('System One Selected');
            $("#supplierSystemOne").removeClass('hideSystem');
            $("#supplierSystemTwo").addClass('hideSystem');
        }
        else{
            alert('error');
        }
    }

    $('#all_customers_check').click(function(){
        let mobile=$('#all_customers_number').val();
       if($(this).is(':checked')){
            $('#set_customer_mobile').val(mobile)
       }
       else{
            $('#set_customer_mobile').val('')
       }
    });
    $('#all_suppliers_check').click(function(){
        let mobile=$('#all_suppliers_number').val();
       if($(this).is(':checked')){
            $('#set_suppliers_mobile').val(mobile)
       }
       else{
            $('#set_suppliers_mobile').val('')
       }
    });

    $('.all_smsGroup_check').click(function(){
     loadCheckNumbers();
    });
    function loadCheckNumbers(){
        $('#number_area_grp').val('')
        let numbers = '';
        $('.all_smsGroup_check').each(function(){
            if($(this).is(':checked')){
                let mobile=$(this).closest('tr').find('.all_smsGroup_checkk').html()
                numbers += mobile
                $('#number_area_grp').val(numbers)
            }
        })
    }

</script>
<script>
    (function($) {
        $.fn.smsArea = function(options) {
            var e = this,
                cutStrLength = 0,
                s = $.extend({
                    cut: true,
                    maxSmsNum: 3,
                    interval: 400,
                    counters: {
                        message: $('.smsCount'),
                        character: $('.smsLength')
                    },
                    lengths: {
                        ascii: [160, 306, 459, 606],
                        unicode: [70, 134, 192, 244]
                    }
                }, options);
            e.keyup(function() {
                clearTimeout(this.timeout);
                this.timeout = setTimeout(function() {
                    var
                        smsType,
                        smsLength = 0,
                        smsCount = -1,
                        charsLeft = 0,
                        text = e.val(),
                        isUnicode = false;
                    for (var charPos = 0; charPos < text.length; charPos++) {
                        switch (text[charPos]) {
                            case "\n":
                            case "[":
                            case "]":
                            case "\\":
                            case "^":
                            case "{":
                            case "}":
                            case "|":
                            case "€":
                                smsLength += 2;
                                break;
                            default:
                                smsLength += 1;
                        }
                        if (text.charCodeAt(charPos) > 127 && text[charPos] != "€") isUnicode = true;
                    }
                    if (isUnicode) {
                        smsType = s.lengths.unicode;
                    } else {
                        smsType = s.lengths.ascii;
                    }
                    for (var sCount = 0; sCount < s.maxSmsNum; sCount++) {
                        cutStrLength = smsType[sCount];
                        if (smsLength <= smsType[sCount]) {
                            smsCount = sCount + 1;
                            charsLeft = smsType[sCount] - smsLength;
                            break
                        }
                    }
                    if (s.cut) e.val(text.substring(0, cutStrLength));
                    smsCount == -1 && (smsCount = s.maxSmsNum, charsLeft = 0);
                    s.counters.message.html(smsCount);
                    s.counters.character.html(charsLeft);
                }, s.interval)
            }).keyup()
        }
    }(jQuery));
    $(function() {
        $('.customer-message-area').smsArea({
            maxSmsNum: 4
        });
        $('.supplier-message-area').smsArea({
            maxSmsNum: 4
        });
        $('.custom-message-area').smsArea({
            maxSmsNum: 4
        });
        $('.custom-message-area2').smsArea({
            maxSmsNum: 4
        });
    })
</script>
@endsection
