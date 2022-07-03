@extends('admin.master')

@section('content')
<main class="app-content">
        <div class="app-title">
          <div>
            <h1><i class="fa fa-edit"></i> Add Software Payment</h1>
            <p>Create Software Payment Form</p>
          </div>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Companies</li>
            <li class="breadcrumb-item"><a href="#">Add Software Payment</a></li>
          </ul>
        </div>
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

                        <form class="form-horizontal" method="POST" action="{{ route('software_payments.store') }}" enctype="multipart/form-data">
                              @csrf
                        <div class="tile">
                            <a href="{{route('software_payments.index')}}" class="btn btn-primary" style="float: right;">
                                <i class="fa fa-eye">&nbsp;View Software Payment</i>
                            </a>
                            <h3 class="tile-title">Software Payment Add</h3>
                            <div class="tile-body">
                              <div class="form-group row">
                                  <label for="" class="control-label col-md-3"> End Date </label>
                                  <div class="col-md-8">
                                    <input type="text" name="software_payment_date" class="form-control dateField" value="{{ old('end_date') ?: '' }}" >
                                    <div class="text-danger">{{ $errors->has('end_date') ? $errors->first('end_date') : '' }}</div>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label for="" class="control-label col-md-3"> Amount to be Paid </label>
                                  <div class="col-md-8">
                                    <input type="number" name="amount" class="form-control" value="{{ old('amount') ?: '0' }}" min="1">
                                    <div class="text-danger">{{ $errors->has('amount') ? $errors->first('amount') : '' }}</div>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label for="" class="control-label col-md-3"> Alert days before </label>
                                  <div class="col-md-8">
                                    <input type="number" name="alert" class="form-control" value="{{ old('alert') ?: '0' }}" min="1">
                                    <div class="text-danger">{{ $errors->has('alert') ? $errors->first('alert') : '' }}</div>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="control-label col-md-3">Payment Status</label>
                                  <div class="col-md-8">
                                    <select name="status" id="" class="form-control">
                                      <option value="1" {{ old('status') == 1 ? 'selected':'' }}>Approved</option>
                                      <option value="0" {{ old('status') == 0 ? 'selected':'' }}>Pending</option>
                                    </select>
                                    <div class="text-danger">{{ $errors->has('status') ? $errors->first('status') : '' }}</div>
                                  </div>
                              </div>

                          </div>
                          <div class="tile-footer">
                            <div class="row">
                              <div class="col-md-12">
                                <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Add</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                      </div>
        </div>
</main>
        @endsection

<script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>


<script type="text/javascript">
  $('.select2').select2();
</script>
