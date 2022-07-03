
@extends('admin.master')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Add User</h1>
                <p>Create User Form</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" style="min-height: 480px">
                @include('partials._alert_message')

                <form class="form-horizontal" action="{{ route('users.store') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="tile">
                        <h3 class="tile-title"><i class="fa fa-plus"></i> Create User
                            <a href="{{ route('users.index') }}" class="btn btn-primary pull-right"><i class="fa fa-list"></i> User List</a>
                        </h3>

                        <div class="tile-body" style="min-height: 540px">
                            @if($companies != null)
                                <div class="form-group row">
                                    <label class="control-label col-md-3">Company</label>
                                    <div class="col-md-8">
                                        <select name="fk_company_id" class="form-control select2">
                                            @foreach ($companies as $id => $company)
                                                <option value="{{ $id }}">{{ $company }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="fk_company_id" value="{{ auth()->user()->fk_company_id }}">
                            @endif

                            <div class="form-group row add_asterisk">
                                <label for="" class="control-label col-md-3">Role</label>
                                <div class="col-md-8">
                                    <select name="role_id" id="" class="form-control select2">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">{{ $errors->has('role_id') ? $errors->first('role_id'):'' }}</div>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">User Name</label>
                                <div class="col-md-8">
                                    <input name="name" class="form-control" type="text" placeholder="Enter User name" value="{{ old('name') ?: '' }}">
                                    <div class="text-danger">{{ $errors->has('name') ? $errors->first('name'):'' }}</div>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">User Phone</label>
                                <div class="col-md-8">
                                    <input name="phone" onkeypress="return event.charCode >= 48 && event.charCode <= 57" class="form-control" type="text" placeholder="Enter User phone" value="{{ old('phone') ?: '' }}">
                                    <div class="text-danger">{{ $errors->has('phone') ? $errors->first('phone'):'' }}</div>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">User Email</label>
                                <div class="col-md-8">
                                    <input name="email" class="form-control" type="email" placeholder="Enter User email address" value="{{ old('email') ?: '' }}">
                                    <div class="text-danger">{{ $errors->has('email') ? $errors->first('email'):'' }}</div>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">User Password</label>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="input-group" >
                                            <input name="password" class="form-control password-toogle" type="password" placeholder="..............." value="">
                                            <div class="input-group-append">
                                                <span style="cursor: pointer" toggle="#input-pwd" class="input-group-text fa fa-fw fa-eye toggle-password"></span>
                                            </div>
                                        </div>
                                      </div>
                                   
                                    <div class="text-danger">{{ $errors->has('password') ? $errors->first('password') : '' }}</div>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Confirm Password</label>
                                <div class="col-md-8">
                                    <input name="confirm_password" class="form-control password-toogle" type="password" placeholder="..............." value="">
                                    <div class="text-danger">{{ $errors->has('confirm_password') ? $errors->first('confirm_password') : '' }}</div>
                                </div>
                            </div>

                            <!-- Image -->
                            <div class="form-group row">
                                <label class="control-label col-md-3">Image</label>
                                <div class="col-md-8">
                                    <input name="image" class="form-control" type="file" value="">
                                    <div class="text-danger">Image size 300 x 250 px</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-11">
                                    <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection


@section('footer-script')
    <script src="{{asset('jq/select2Loads.js')}}"></script>
    <script>
        $('.toggle-password').on('click', function() {
        $(this).toggleClass('fa-eye fa-eye-slash');
        let input = $('.password-toogle');
        (input.attr('type') == 'password') ? input.attr('type', 'text') : input.attr('type', 'password')
        });
    </script>
@stop
