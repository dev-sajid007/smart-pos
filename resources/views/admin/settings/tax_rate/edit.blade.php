@extends('admin.master')
@section('content')

<main class="app-content">
        <div class="app-title">
          <div>
            <h1><i class="fa fa-edit"></i> System Settings</h1>
            <p>Create Company Form</p>
          </div>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Companies</li>
            <li class="breadcrumb-item"><a href="#">Add Company</a></li>
          </ul>
        </div>
        <div class="row">
                <div class="col-md-12">
                        <div class="tile">
                          <h3 class="tile-title">Update Tax rate</h3>
                          <div class="tile-body">
                            <form class="form-horizontal" action="{{route('tax_rates.update',$tax->id)}}" method="post">
                              @csrf
                              @method('PUT')
                              <div class="form-group row add_asterisk">
                                    <label class="control-label col-md-3">Title</label>
                                    <div class="col-md-8">
                                    <input name="tax_rate_title" value="{{$tax->tax_rate_title}}" class="form-control" type="text" placeholder="Title">
                                    <input name="user_id" type="hidden" value="{{$user_id_session }}">
                                    </div>
                              </div>


                              <div class="form-group row add_asterisk">
                                    <label class="control-label col-md-3">Rate</label>
                                    <div class="col-md-8">
                                    <input name="amount"  value="{{$tax->amount}}" class="form-control" type="text" placeholder="Rate">
                                    </div>
                              </div>


                              <div class="form-group row add_asterisk">
                                    <label class="control-label col-md-3">Type</label>
                                    <div class="col-md-8">
                                    <select name="tax_rate_type" class="form-control" >
                                      <option value="{{$tax->tax_rate_type}}">
                                        @if($tax->tax_rate_type == 0)
                                          Percentage (%)
                                        @elseif($tax->tax_rate_type == 1)
                                          Fixed ($)
                                        @endif

                                      </option>

                                      <option value="0">Percentage (%)</option>
                                      <option value="1">Fixed ($)</option>
                                    </select>
                                    </div>
                              </div>

                              <div class="tile-footer">
                              <div class="row">
                                <div class="col-md-12">
                                  <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update Tax Rate</button>
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


<script type="text/javascript">
  $('.select2').select2();
</script>
        @endsection