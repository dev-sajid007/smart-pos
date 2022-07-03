
@extends('admin.master')
@section('title', 'User Edit')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/custom_css/image_full_screen.css') }}">
@endsection

@section('content')

    <main class="app-content">
        <!-- The Modal -->
        @include('admin.includes.image_full_screen')

        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Edit User</h1>
                <p>Update User Form</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                @include('partials._alert_message')

                <form class="form-horizontal" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data" method="POST">
                    @csrf   @method('PUT')


                    <!-- Company -->
                    <div class="tile">
                        <h3 class="tile-title"><i class="fa fa-edit"></i> User Edit
                            <a href="{{ route('users.index') }}" class="btn btn-primary pull-right"><i class="fa fa-list"></i> User List</a>
                        </h3>

                        <div class="tile-body">
                            @if(isset($companies))
                                <div class="form-group row">
                                    <label class="control-label col-md-3">Company</label>
                                    <div class="col-md-8">
                                        <select name="fk_company_id" class="form-control select2">
                                            @foreach ($companies as $id => $company)
                                                <option value="{{ $id }}" {{ $id == $user->fk_company_id ? 'selected':'' }}>{{ $company }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="fk_company_id" value="{{ $user->fk_company_id }}">
                            @endif


                            <!-- User Role -->
                            <div class="form-group row add_asterisk">
                                <label for="" class="control-label col-md-3">Role</label>
                                    <div class="col-md-8">
                                        <select name="role_id" id="" class="form-control select2">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}" {{ $role->id == optional($user->user_role)->role_id ? 'selected':'' }}>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">{{ $errors->has('role_id') ? $errors->first('role_id'):'' }}</div>
                                    </div>
                                </div>

                                <!-- User name -->
                                <div class="form-group row add_asterisk">
                                    <label class="control-label col-md-3">User Name</label>
                                    <div class="col-md-8">
                                        <input name="name" class="form-control" type="text" placeholder="Enter User name" value="{{ old('name') ?: $user->name }}">
                                        <div class="text-danger">{{ $errors->has('name') ? $errors->first('name'):'' }}</div>
                                    </div>
                                </div>

                                <!-- User mobile number -->
                                <div class="form-group row add_asterisk">
                                    <label class="control-label col-md-3">User Phone</label>
                                    <div class="col-md-8">
                                        <input name="phone" class="form-control" type="text" placeholder="Enter User phone" value="{{ old('phone') ?? $user->phone }}">
                                        <div class="text-danger">{{ $errors->has('phone') ? $errors->first('phone'):'' }}</div>
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

                                <!-- Email -->
                                <div class="form-group row add_asterisk">
                                    <label class="control-label col-md-3">User Email</label>
                                    <div class="col-md-8">
                                        <input  name="email" class="form-control" type="email" placeholder="Enter User email address" value="{{ old('email', $user->email) }}">
                                        <div class="text-danger">{{ $errors->has('email') ? $errors->first('email'):'' }}</div>
                                    </div>
                                </div>


                                <br>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 offset-3">
                                    @if (file_exists($user->image))
                                        <img width="100" class="full-screen-image" style="cursor: pointer" title="Click on image to fullscreen" height="100" src="{{ asset($user->image) }}">
                                    @endif
                                </div>
                                <div class="col-md-5">
                                    <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-fw fa-lg fa-edit"></i>Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- #######################      CHANGE PASSWORD     ######################## -->
        @include('admin.user.users.update-password')
    </main>
@endsection

@section('footer-script')
    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <script  src="{{ asset('assets/admin/js/plugins/select2.min.js') }}"></script>
    <script src="{{ asset('jq/select2Loads.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/custom_js/image_full_screen.js') }}"></script>
    <script>
        $('.toggle-password').on('click', function() {
        $(this).toggleClass('fa-eye fa-eye-slash');
        let input = $('.password-toogle');
        (input.attr('type') == 'password') ? input.attr('type', 'text') : input.attr('type', 'password')
        });
    </script>
@stop
