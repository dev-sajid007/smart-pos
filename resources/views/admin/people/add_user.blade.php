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
                          <h3 class="tile-title">Add New User</h3>
                          <div class="tile-body">
                            <form class="form-horizontal">

                              

                              <div class="form-group row">
                                    <label class="control-label col-md-3">First Name</label>
                                    <div class="col-md-8">
                                    <input name="first_name" class="form-control" type="text" placeholder="First Name">
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Last Name</label>
                                    <div class="col-md-8">
                                    <input name="last_name" class="form-control" type="text" placeholder="Last Name">
                                    </div>
                              </div>


                              <div class="form-group row">
                                    <label class="control-label col-md-3">Company</label>
                                    <div class="col-md-8">
                                    <input name="company" class="form-control" type="text" placeholder="Company">
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Phone</label>
                                    <div class="col-md-8">
                                    <input name="phone" class="form-control" type="number" placeholder="Phone">
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Email</label>
                                    <div class="col-md-8">
                                    <input name="email" class="form-control" type="email" placeholder="Email">
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">User Role</label>
                                    <div class="col-md-8">
                                    
                                      <div class="bs-component" style="margin-bottom: 15px;">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                          <label class="btn btn-primary">
                                            <input id="option1" type="radio" name="options" autocomplete="off"> Owner
                                          </label>
                                          <label class="btn btn-primary">
                                            <input id="option2" type="radio" name="options" autocomplete="off"> Admin
                                          </label>
                                          <label class="btn btn-primary">
                                            <input id="option3" type="radio" name="options" autocomplete="off"> Purchaser
                                          </label>
                                          <label class="btn btn-primary">
                                            <input id="option3" type="radio" name="options" autocomplete="off"> Salesman
                                          </label>
                                          <label class="btn btn-primary">
                                            <input id="option3" type="radio" name="options" autocomplete="off"> User
                                          </label>
                                        </div>
                                      </div>

                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Email</label>
                                    <div class="col-md-8">
                                    <input name="email" class="form-control" type="email" placeholder="Email">
                                    </div>
                              </div>

                              
                              


                              <div class="tile-footer">
                            <div class="row">
                              <div class="col-md-8 col-md-offset-3">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Add Discount</button>
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