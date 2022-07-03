@extends('admin.master')
@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-edit"></i> Add Software Billing</h1>
      <p>Create Software Billing Form</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item">Companies</li>
      <li class="breadcrumb-item"><a href="#">Add Software Billing</a></li>
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

      <form class="form-horizontal" method="POST" action="{{ route('company_packages.store') }}"
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="software_payment_date" value="{{ date('Y-m-d') }}">
        <input type="hidden" name="status" value="0">


        <div class="tile">
          <a href="{{route('company_packages.index')}}" class="btn btn-primary" style="float: right;"><i
              class="fa fa-eye"></i>View Software Billing</a>
          <h3 class="tile-title">Software Billing Add</h3>
          <div class="tile-body">

            <div class="form-group row">
              <label class="control-label col-md-3">Select Company</label>
              <div class="col-md-8">
                <select name="fk_company_id" class="form-control select2">
                  @foreach ($companies as $id => $company)
                  <option value="{{ $id }}" {{ old('fk_company_id') == $id ? 'selected':'' }}>{{ $company }}</option>
                  @endforeach
                </select>
                <div class="text-danger">{{ $errors->has('fk_company_id') ? $errors->first('fk_company_id') : '' }}</div>
              </div>
            </div>


            <div class="form-group row">
              <label for="" class="control-label col-md-3">Billing</label>
              <div class="col-md-8">
                <select name="fk_billing_cycle_id" id="" class="form-control select2">
                  @foreach ($billing_cycles as $id => $billing_cycle)
                  <option value="{{ $id }}" {{ old('fk_billing_cycle_id') == $id ? 'selected':'' }}>{{ $billing_cycle }}
                  </option>
                  @endforeach
                </select>
                <div class="text-danger">{{ $errors->has('fk_billing_cycle_id') ? $errors->first('fk_billing_cycle_id') : '' }}</div>
              </div>
            </div>

            <div class="form-group row">
              <label for="" class="control-label col-md-3">Payable Amount</label>
              <div class="col-md-8">
                <input type="number" name="amount" class="form-control" value="{{ old('amount') ?: '0' }}">
                <div class="text-danger">{{ $errors->has('amount') ? $errors->first('amount') : '' }}</div>
              </div>
            </div>

            <div class="form-group row">
              <label class="control-label col-md-3">Trial Period</label>
              <div class="col-md-8">
                <input type="number" class="form-control" name="trial_period" value="10">
                <div class="text-danger">{{ $errors->has('trial_period') ? $errors->first('trial_period'):'' }}</div>
              </div>
            </div>


            <div class="form-group row">
              <label class="control-label col-md-3">Alert Before</label>
              <div class="col-md-8">
                <input type="number" class="form-control" name="alert_before" value="10">
                <div class="text-danger">{{ $errors->has('alert_before') ? $errors->first('alert_before'):'' }}</div>
              </div>
            </div>


            <div class="form-group row">
              <label class="control-label col-md-3">Description</label>
              <div class="col-md-8">
                <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
                <div class="text-danger">{{ $errors->has('description') ? $errors->first('description') : '' }}</div>
              </div>
            </div>

          </div>
          <div class="tile-footer">
            <div class="row">
              <div class="col-md-12">
                <button class="btn btn-primary pull-right" type="submit"><i
                    class="fa fa-fw fa-lg fa-check-circle"></i>Add</button>
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