@extends('admin.master')
@section('title', ' - User Roles')
@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-th-list"></i> Role Information</h1>
                <p>Role information</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Role Information</li>
                <li class="breadcrumb-item active"><a href="#">Role Information Table</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if(Session::get('message'))
                    <div class="alert alert-success">
                        {{ Session::get('message') }}
                    </div>
                @endif
                @if(Session::get('error_message'))
                    <div class="alert alert-danger">
                        {{ Session::get('error_message') }}
                    </div>
                @endif
                <div class="tile">
                    @if(hasPermission('roles.create'))
                        <a href="{{ route('roles.create') }}" class="btn btn-primary" style="float: right;"><i
                                class="fa fa-plus"></i> Add Role</a>
                    @endif
                    <h3 class="tile-title">User Roles</h3>
                    <div class="tile-body table-responsive" style="min-height: 440px">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th>Role Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th width="12%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($roles as $key => $role)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->description }}</td>
                                        <td>
                                            {!!
                                                $role->status == 1
                                                ? '<span class="badge badge-success">Active</span>'
                                                :'<span class="badge badge-danger">Inactive</span>'
                                            !!}
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                @if ($role->id != 1)
                                                    @if(hasPermission('roles.edit'))
                                                        <a class="btn btn-info btn-sm text-light" title="Edit"
                                                           href="{{ route('roles.edit', $role->id) }}"> <i
                                                                class="fa fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if(hasPermission('permissions.assign'))
                                                        <a class="btn btn-success btn-sm" title="Assign Permission"
                                                           href="{{ route('permissions.assign', $role->id) }}"> <i
                                                                class="fa fa-lock"></i>
                                                        </a>
                                                    @endif

                                                    @if(hasPermission('roles.destroy'))
                                                        <button type="button" data-toggle="modal"
                                                                onclick="deleteData({{ $role->id }})" data-target="#DeleteModal"
                                                                class="btn btn-danger btn-sm" title="Delete"><i
                                                                class="fa fa-trash"></i></button>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @include('admin.includes.pagination', ['data' => $roles])
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('admin.includes.delete_confirm')

    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>

    <script>

        function deleteData(id) {
            var id = id;
            var url = '{{ route("roles.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>

    @include('admin.includes.delete_confirm')


@endsection
