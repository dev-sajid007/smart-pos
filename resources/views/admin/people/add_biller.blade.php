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
                          <h3 class="tile-title">Add New Biller</h3>
                          <div class="tile-body">
                            <form class="form-horizontal">

                              

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Name</label>
                                    <div class="col-md-8">
                                    <input name="name" class="form-control" type="text" placeholder="Name">
                                    </div>
                              </div>


                              <div class="form-group row">
                                    <label class="control-label col-md-3">Email</label>
                                    <div class="col-md-8">
                                    <input name="email" class="form-control" type="email" placeholder="Email">
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Phone</label>
                                    <div class="col-md-8">
                                    <input name="phone" class="form-control" type="number" placeholder="Phone">
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Company</label>
                                    <div class="col-md-8">
                                    <input name="company" class="form-control" type="text" placeholder="Company">
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Address</label>
                                    <div class="col-md-8">
                                    <textarea name="address" class="form-control" rows="5" type="text" placeholder="Address"></textarea>
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">City</label>
                                    <div class="col-md-8">
                                    <input name="city" class="form-control" type="text" placeholder="City">
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">State</label>
                                    <div class="col-md-8">
                                    <input name="state" class="form-control" type="text" placeholder="State">
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Postal Code</label>
                                    <div class="col-md-8">
                                    <input name="postal_code" class="form-control" type="number" placeholder="Postal Code">
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Country</label>
                                    <div class="col-md-8">
                                    <input name="country" class="form-control" type="text" placeholder="Country">
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Logo</label>
                                    <div class="col-md-8">
                                    <select name="category_code" class="form-control select2" >
                                      <option>Select Type</option>
                                      <option>Percentage (%)</option>
                                      <option>Fixed ($)</option>
                                      
                                    </select>
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Biller Custom Field-1</label>
                                    <div class="col-md-8">
                                    <input name="biller_custom_field_1" class="form-control" type="text" placeholder="Custom Field-1">
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Biller Custom Field-2</label>
                                    <div class="col-md-8">
                                    <input name="biller_custom_field_2" class="form-control" type="text" placeholder="Custom Field-2">
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Biller Custom Field-3</label>
                                    <div class="col-md-8">
                                    <input name="biller_custom_field_3" class="form-control" type="text" placeholder="Custom Field-3">
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Biller Custom Field-4</label>
                                    <div class="col-md-8">
                                    <input name="biller_custom_field_4" class="form-control" type="text" placeholder="Custom Field-4">
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Biller Custom Field-5</label>
                                    <div class="col-md-8">
                                    <input name="biller_custom_field_5" class="form-control" type="text" placeholder="Custom Field-5">
                                    </div>
                              </div>

                              <div class="form-group row">
                                    <label class="control-label col-md-3">Biller Custom Field-6</label>
                                    <div class="col-md-8">
                                    <input name="biller_custom_field_6" class="form-control" type="text" placeholder="Custom Field-6">
                                    </div>
                              </div>

                              
                              <div class="form-group row">
                                    <label class="control-label col-md-3">Invoice Footer</label>
                                    <div class="col-md-8">
                                    <textarea name="invoice_footer" class="form-control" rows="5" type="text" placeholder="Invoice Footer"></textarea>
                                    </div>
                              </div>
                              


                              <div class="tile-footer">
                                <div class="row">
                                  <div class="col-md-8 col-md-offset-3">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Add Biller</button>
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