@extends('admin.master')
@section('content')
<main class="app-content">
        <div class="row user">
          <div class="col-md-12">
            <div class="profile">
              <div class="info"><img class="user-img" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/128.jpg">
                <h4>{{ $user->name }}</h4>
                <p>FrontEnd Developer</p>
              </div>
              <div class="cover-image"></div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="tile p-0">
              <ul class="nav flex-column nav-tabs user-tabs">
                <li class="nav-item"><a class="nav-link active" href="#user-timeline" data-toggle="tab">User Information</a></li>
                <li class="nav-item"><a class="nav-link" href="#user-settings" data-toggle="tab">Change Password</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md-9">
            <div class="tab-content">
              <div class="tab-pane active" id="user-timeline">
                <div class="timeline-post">
                  <div class="post-media"><a href="#"><img src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg"></a>
                    <div class="content">
                      <h5><a href="#">{{ $user->name }}</a></h5>
                      <p class="text-muted"><small>2 January at 9:30</small></p>
                    </div>
                  </div>
                  <div class="post-content">
                    
                      <div class="table-responsive">
                        <table class="table">
                          <thead>
                            <tr>
                              <th>Name</th>
                              <th>Email</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>{{ $user->name }}</td>
                              <td>{{ $user->email }}</td>
                            </tr>
                          </tbody>
                        </table>
                    </div>

                  </div>

                </div>
                
              </div>
              <div class="tab-pane fade" id="user-settings">
                <div class="tile user-settings">
                  <h4 class="line-head">Change Password</h4>
                  <form>
                    <div class="row">
                      <div class="col-md-8 mb-4">
                        <label>Name</label>
                        <input class="form-control" type="text" value="{{ $user->name }}" readonly="readonly">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8 mb-4">
                        <label>Email</label>
                        <input class="form-control" type="text" value="{{ $user->email }}" readonly="readonly">
                      </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="row">
                      <div class="col-md-8 mb-4">
                        <label>Enter New Password</label>
                        <input class="form-control" type="password" value="" name="password" required="required">
                      </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="row mb-10">
                      <div class="col-md-12">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Save</button>
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