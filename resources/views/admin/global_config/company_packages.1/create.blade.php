@extends('admin.master')
@section('content')

<main class="app-content">
        <div class="app-title">
          <div>
            <h1><i class="fa fa-edit"></i> Add Company Billing</h1>
            <p>Create Company Billing Form</p>
          </div>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Companies</li>
            <li class="breadcrumb-item"><a href="#">Add Company Billing</a></li>
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

                        <form class="form-horizontal" method="POST" action="{{ route('company_packages.store') }}" enctype="multipart/form-data">
                              @csrf
                      
                          <div class="tile">
                              <a href="{{route('company_packages.index')}}" class="btn btn-primary" style="float: right;"><i class="fa fa-eye"></i>View Company Billing</a>
                          <h3 class="tile-title">Company Billing Add</h3>
                          <div class="tile-body">
                              
                            <div class="form-group row">
                              <label class="control-label col-md-3">Select Company</label>
                              <div class="col-md-8">
                                <select name="fk_company_id" class="form-control select2">
                                    @foreach ($companies as $id => $company)
                                        <option value="{{ $id }}" {{ old('fk_company_id') == $id ? 'selected':'' }}>{{ $company }}</option>
                                    @endforeach
                                </select>
                              </div>
                            </div>


                              <div class="form-group row">
                                  <label for="" class="control-label col-md-3">Billing</label>
                                  <div class="col-md-8">
                                    <select name="fk_package_id" id="" class="form-control select2">
                                        @foreach ($packages as $id => $package)
                                            <option value="{{ $id }}" {{ old('fk_package_id') == $id ? 'selected':'' }}>{{ $package }}</option>
                                        @endforeach
                                    </select>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label for="" class="control-label col-md-3">Paid Amount</label>
                                  <div class="col-md-8">
                                    <input type="number" name="amount" class="form-control" value="{{ old('amount') ?: '0' }}">
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="control-label col-md-3">Payment Status</label>
                                  <div class="col-md-8">
                                    <select name="status" id="" class="form-control">
                                      <option value="0" {{ old('status') == 0 ? 'selected':'' }}>Pending</option>
                                      <option value="1" {{ old('status') == 1 ? 'selected':'' }}>Approved</option>
                                    </select>
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
<script  src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>


<script type="text/javascript">
  $('.select2').select2();
</script>