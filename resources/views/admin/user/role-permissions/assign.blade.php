@extends('admin.master')
@section('title', ' - Role Permission edit')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> User Permission</h1>
                <p>Permission Assign Form</p>
            </div>

            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Permissions</li>
                <li class="breadcrumb-item"><a href="#">Add Permission</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <div class="tile">
                    <a href="{{ route('roles.index') }}" class="btn btn-primary pull-right" style="float: right;">
                        <i class="fa fa-eye"></i> View Permission
                    </a>
                    <h3 class="tile-title">Roll: {{ $role->name }}</h3>

                    <hr>

                    <div class="tile-body pr-3 pl-3">
                        <form class="form-horizontal" method="POST" action="{{ route('permissions.assign.store', $role->id) }}">
                            @csrf
                            <div class="form-group row">
                                <div class="animated-checkbox">
                                    <label>
                                        <input type="checkbox" name="checkAll[]" id="modules" class="form-control check-all">
                                        <span class="label-text">Check All</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                @foreach($modules as $key => $module)
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="animated-checkbox">
                                                <label>
                                                    <input type="checkbox" name="modules[]" id="modules" class="form-control module check">
                                                    <span class="label-text">{{ $module->name }}</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-sm-10">
                                            <div class="row">
                                                @foreach ($module->permissions as $k => $permission)
                                                        <div class="animated-checkbox col-sm-3">
                                                            <label>
                                                                <input type="checkbox" name="checked_permission_ids[]"
                                                                       {{ in_array($permission->slug_name, $isPermitted) ? 'checked' : '' }}
                                                                       class="form-control permission check" value="{{ $permission->id }}">
                                                                <span class="label-text">{{ $permission->name }}</span>
                                                            </label>
                                                        </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="margin: 0; margin-top: 10px; margin-bottom: 10px; padding: 0">
                                @endforeach
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary float-right"><i class="fa fa-check"></i> Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('footer-script')
    <script>
        $('.module').click(function () {
            $(this).closest(".row").find('.permission').prop('checked', $(this).is(':checked'))
        })
        $('.check-all').click(function () {
            $(this).closest("form").find('.check').prop('checked', $(this).is(':checked'))
        })
    </script>
@endsection