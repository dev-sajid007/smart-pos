@extends('admin.master')
@section('content')

<main class="app-content">
        <div class="app-title">
          <div>
            <h1><i class="fa fa-edit"></i> Settings</h1>
            <p>Create Package Form</p>
          </div>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Packages</li>
            <li class="breadcrumb-item"><a href="#">Edit Package</a></li>
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

                        <div class="tile">
                          <a href="{{route('packages.index')}}" class="btn btn-primary pull-right" style="float: right;"><i class="fa fa-eye"></i>View Package</a>
                          <h3 class="tile-title">Edit New Package</h3>
                          <div class="tile-body">
                            <form class="form-horizontal" method="POST" action="{{ route('packages.update', $package->id) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="fk_created_by" value="{{ $user_id_session }}">
                                <input type="hidden" name="fk_updated_by" value="{{ $user_id_session }}">
                                
                              <div class="form-group row add_asterisk">
                                    <label class="control-label col-md-3">Name</label>
                                    <div class="col-md-8">
                                    <input name="name" value="{{ old('name') ?: $package->name }}" class="form-control" type="text" placeholder="Name">
                                    <div class="text-danger">
                                        {{ $errors->has('name') ? $errors->first('name'):'' }}
                                    </div>
                                    </div>
                              </div>


                              <div class="form-group row add_asterisk">
                                  <label class="control-label col-md-3">Billing Cycle</label>
                                  <div class="col-md-8">
                                    <select name="fk_billing_cycle_id" id="" class="form-control select2">
                                      @foreach ($billing_cycles as $id => $billing_cycle)
                                          <option value="{{ $id }}" {{ $id==$package->fk_billing_cycle_id ? 'selected':'' }}>{{ $billing_cycle }}</option>
                                      @endforeach
                                    </select>
                                  </div>
                              </div>

                              <div class="form-group row add_asterisk">
                                <label for="" class="control-label col-md-3">Trial Period</label>
                                <div class="col-md-8">
                                  <input type="number" min="1" class="form-control" name="trial_period" placeholder="Trial Period" value="{{ old('trial_period') ?: $package->trial_period }}">
                                  <div class="text-danger">
                                      {{ $errors->has('trial_period') ? $errors->first('trial_period'):'' }}
                                  </div>
                                </div>

                              </div>

                              <div class="form-group row">
                                  <label class="control-label col-md-3">Alert Before(Days)</label>
                                    
                                  <div class="col-md-8">
                                      <input type="number" name="alert_before" id="" class="form-control" value="{{ old('alert_before') ?: $package->alert_before }}">
                                      <div class="text-danger">
                                        {{ $errors->has('alert_before') ? $errors->first('alert_before'):'' }}
                                      </div>
                                  </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Description</label>
                                    <div class="col-md-8">
                                    <textarea name="description" class="form-control" rows="5" type="text" placeholder="Description">{{ old('description') ?: $package->description }}</textarea>
                                    <div class="text-danger">
                                        {{ $errors->has('description') ? $errors->first('description'):'' }}
                                    </div>
                                    </div>
                              </div>

                              <div class="tile-footer">
                                <div class="row">
                                  <div class="col-md-12">
                                    <button class="btn btn-primary pull-right" type="submit" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Edit Package</button>
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