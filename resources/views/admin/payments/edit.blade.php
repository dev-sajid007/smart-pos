@extends('admin.master')
@section('content')

<main class="app-content">
        <div class="app-title">
          <div>
            <h1><i class="fa fa-edit"></i> System Settings</h1>
            <p>Create Debit Voucher Form</p>
          </div>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Debit Vouchers</li>
            <li class="breadcrumb-item"><a href="#">Update Debit Voucher</a></li>
          </ul>
        </div>
        <div class="row">
                <div class="col-md-12">
                  @if($errors->any())
                  <ul class="alert-danger list-unstyled">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                  @endif
                        <div class="tile">
                          <a href="{{ route('payments.index') }}" class="btn btn-primary pull-right" style="float: right;"><i class="fa fa-eye"></i>View Payment</a>
                          <h3 class="tile-title">Update New Debit Voucher</h3>
                          <div class="tile-body">
                            <form class="form-horizontal" method="post" action="{{ route('payments.update', $voucher->id) }}">
                              @csrf
                              @method("PUT")
                              <input type="hidden" name="id" value="{{ $voucher->id }}">
                              <input type="hidden" name="fk_company_id" value="{{ $company_id_session }}">
                              <input type="hidden" name="fk_created_by" value="{{ $user_id_session }}">
                              <input type="hidden" name="fk_updated_by" value="{{ $user_id_session }}">
                              <input type="hidden" name="voucher_type" value="debit">
                              <input type="hidden" name="fk_approved_by" value="{{ $user_id_session }}">
                              <input type="hidden" name="approved_at" value="{{ date('Y-m-d G:i:s') }}">

                              <div class="form-group row">

                                    <div class="col-md-4">
                                      <label class="control-label">Pay To:</label>
                                      <select name="fk_party_id" id="" class="form-control select2">
                                        @foreach ($parties as $party)
                                          <option value="{{ $party->id }}" {{ $voucher->fk_party_id == $party->id ? 'selected':'' }}>{{ $party->name }}</option>
                                        @endforeach  
                                      </select>
                                      <div class="text-danger">
                                        {{ $errors->has('fk_party_id') ? $errors->first('fk_party_id'):'' }}
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <label class="control-label">Ref(#ID):</label>
                                      <input type="text" class="form-control" name="voucher_reference" placeholder="Voucher Reference" value="{{ $voucher->voucher_reference }}">
                                      <div class="text-danger">
                                        {{ $errors->has('voucher_reference') ? $errors->first('voucher_reference'):'' }}
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <label class="control-label">Payment Date:</label>
                                      <input name="voucher_date" class="form-control" id="demoDate" type="text" value="{{ $voucher->voucher_date ?: date('Y-m-d') }}">
                                      <div class="text-danger">
                                        {{ $errors->has('voucher_date') ? $errors->first('voucher-date'):'' }}
                                      </div>
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <div class="col-md-12 table-responsive">
                                      <table class="table">
                                        <tr>
                                          <th>Select Account</th>
                                          <th>Description</th>
                                          <th>Payable Amount</th>
                                          <th>Paid</th>
                                        </tr>
                                        @foreach($voucher_account_charts as $voucher_account_chart)
                                        <input type="hidden" name="voucher_account_chart_id" value="{{ $voucher_account_chart->id }}">
                                        <tr>
                                          <td>
                                              <select name="fk_account_chart_id[]" id="" class="form-control select2" >
                                                @foreach($account_charts as $value)
                                                <option value="{{$value->id}}" {{ $value->id == $voucher_account_chart->fk_account_chart_id ? 'selected':'' }}>{{ $value->head_name}}</option>
                                                @endforeach
                                              </select>
                                          </td>
                                          <td width="40%">
                                            <input name="description[]" class="form-control" type="text" placeholder="Description" value="{{ $voucher_account_chart->description }}">
                                          </td>
                                          <td>
                                            <input name="payable_amount_unit[]" class="form-control payable_amount1" type="number" placeholder="Payable" value="{{ $voucher_account_chart->payable_amount }}" onkeyup="show_payable_sum()">
                                          </td>

                                          <td>
                                            <input name="paid_amount_unit[]" class="form-control paid_amount1" type="number" placeholder="Paid" value="{{ $voucher_account_chart->paid_amount }}" onkeyup="show_paid_sum()">
                                          </td>
                                        </tr>
                                        @endforeach
                                      </table>
                                       
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <label class="control-label col-md-3">Select Account</label>
                                            <div class="col-md-9">
                                              <select name="fk_account_id" class="form-control" >
                                                @foreach($accounts as $value)
                                                <option value="{{$value->id}}" {{ $value->id == $voucher->fk_account_id ? 'selected':'' }}>{{$value->account_name}}, {{$value->account_no}}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger">
                                              {{ $errors->has('fk_account_id') ? $errors->first('fk_account_id'):'' }}
                                            </div>
                                            </div>
                                          </div>
                                          <br>
                                          <div class="row">
                                            <label class="control-label col-md-3">Select Method</label>
                                            <div class="col-md-9">
                                              <select name="fk_payment_id" class="form-control" >
                                              @foreach($payment_methods as $value)
                                              <option value="{{$value->id}}" {{ $value->id == $voucher->fk_payment_id ? 'selected':'' }}>{{$value->method_name}}</option>
                                              @endforeach
                                            </select>
                                            <div class="text-danger">
                                              {{ $errors->has('fk_payment_id') ? $error->first('fk_payment_id'):'' }}
                                            </div>
                                            </div>
                                          </div>
                                          <br>
                                          <div class="row">
                                            <label class="control-label col-md-3">cheque No</label>
                                            <div class="col-md-9">
                                              <input name="cheque_number"  class="form-control" type="text" placeholder="Cheque No" value="{{ $voucher->cheque_number }}">
                                            </div>
                                          </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <label class="control-label col-md-3">Payable Amount</label>
                                            <div class="col-md-9">
                                              <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                                <input class="form-control" readonly="readonly" name="payable_amount" id="payable_amount" type="text" value="{{ $voucher->payable_amount }}">
                                              </div>

                                            </div>
                                          </div>
                                          <br>
                                          <div class="row">
                                            <label class="control-label col-md-3">Pay Amount</label>
                                            <div class="col-md-9">
                                              <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                                <input class="form-control paidamount" name="paid_amount" readonly="readonly" id="paid_amount" type="text" value="{{ $voucher->paid_amount }}">
                                              </div>
                                            </div>
                                          </div>
                                          <br>
                                          <div class="row">
                                            <label class="control-label col-md-3">Due Amount</label>
                                            <div class="col-md-9">
                                              <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                                <input class="form-control" name="due_amount" id="due_amount" value="{{ $voucher->due_amount }}" readonly="readonly" id="exampleInputAmount" type="text" value="0">
                                              </div>
                                            </div>
                                          </div>
                                    </div>
                              </div>
                              <div class="tile-footer">
                            <div class="row">
                              <div class="col-md-12">
                                <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update Payment</button>
                              </div>
                            </div>
                          </div>
                            </form>
                          </div>
                          
                        </div>
                      </div>
        </div>
</main>

  <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
  <script  src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>
  <script src="{{ asset('assets/admin/js/plugins/bootstrap-datepicker.min.js')}}"></script>




<script type="text/javascript">
  $('.addRow').on('click',function () {
      addRow('parts_table');
  });
  function addRow(tableId) {
    var table = document.getElementById(tableId);
      var tr = '<tr>' +
          '<td><select class=\"form-control\" name=\"fk_account_chart_id[]\" id=\"\">@foreach($account_charts as $value)<option value=\"{{$value->id}}\">{{$value->head_name}}</option>@endforeach</select></td>'+
          
          '<td><input type=\"text\" min=\"0\" step=\"any\" name=\"description[]\" id=\"costPerUnit\" class=\"form-control changesNo\" autocomplete=\"off\" placeholder=\"Description\" onkeyup=\"mul()\"></td>'+
          
          '<td><input type=\"number\" min=\"0\" step=\"any\" name=\"payable_amount_unit[]\" id=\"\" class=\"form-control payable_amount1 changesNo\" autocomplete=\"off\" placeholder=\"Payable\" onkeyup=\"show_payable_sum()\"></td>'+


          '<td><input type=\"number\" min=\"0\" step=\"any\" name=\"paid_amount_unit[]\" id=\"\"  class=\"form-control paid_amount1\" onkeyup=\"show_paid_sum()\" placeholder=\"Paid\"></td>'+
          '<td><button class=\"btn btn-danger btn-md remove delete\" name=\"btn\" type=\"button\"><span class=\"fa fa-trash-o\"></span> </button></td>'+
          '</tr>';
      $('tbody').append(tr);
  };
  $('tbody').on('click','.remove', function () {
      $(this).parent().parent().remove();
  });


$(document).ready(function(){

    $('form').on('focus', 'input[type=number]', function(e){
    $(this).on('mousewheel.disableScroll', function(e){
      e.preventDefault()
    })
    });

      // Restore scroll on number inputs.
      $('form').on('blur', 'input[type=number]', function(e) {
          $(this).off('wheel');
      });

      // Disable up and down keys.
      $('form').on('keydown', 'input[type=number]', function(e) {
          if ( e.which == 38 || e.which == 40 )
              e.preventDefault();
      });  

    });
      

  function show_due_amount(){

      var due_amount = 0;
      var payable = $("#payable_amount").val();
      var paid = $("#paid_amount").val();
      due_amount = payable-paid;
      
      $("#due_amount").val(due_amount.toFixed(2));
  
  }

  function show_payable_sum(){
    $(document).ready(function(){
      var total_payable = 0;
      $(".payable_amount1").each(function(i, payableamount){
      var p = $(payableamount).val();
      total_payable += p ? parseFloat(p):0;
      $("#payable_amount").val(total_payable);
    });
    show_due_amount();

    });
    
   
  }



  function show_paid_sum(){
    $(document).ready(function(){
      var total_paid = 0;
      $(".paid_amount1").each(function(i, paidamount){
      var p = $(paidamount).val();
      total_paid += p ? parseFloat(p):0;

      $("#paid_amount").val(total_paid);
      
    });

    show_due_amount();
    });
    
  }
</script>


<script type="text/javascript">

  $('#demoDate').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true
      });


  $('.select2').select2();
</script>
        @endsection