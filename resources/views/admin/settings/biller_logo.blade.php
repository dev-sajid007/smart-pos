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
                          <h3 class="tile-title">Update Biller Logo</h3>
                          <div class="tile-body row">


                              <div class="col-md-6">
                                <form class="form-horizontal">
                                  <label>Biller Logo</label>
                                  <input type="file" class="form-control" name="biller_logo">

                                  <div class="tile-footer">
                                      <div class="row">
                                        <div class="col-md-8 col-md-offset-3">
                                          <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update Logo</button>
                                        </div>
                                      </div>
                                  </div>





                                </form>
                              </div>


                          </div>
                          
                        </div>
                </div>



        </div>
</main>
        @endsection