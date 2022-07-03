@extends('admin.master')
@section('title', ' - Permission')
@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                @if(Session::get('message'))
                    <div class="alert alert-success">
                        {{ Session::get('message') }}
                    </div>
                @endif

                <div class="tile">
                    <a href="{{ route('permissions.create') }}" class="btn btn-primary" style="float: right;">
                        <i class="fa fa-plus"></i> Add Permission
                    </a>
                    <h3 class="tile-title">Permission List</h3>

                    <div class="tile-body table-responsive">
                        <table class="table table-hover table-bordered" id="">
                            <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th>Permission Name</th>
                                    <th>Module Name</th>
                                    <th>Description</th>
                                    <th>Slug</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($permissions as $key => $permission)
                                    <tr>
                                        <th>{{ $key + $permissions->firstItem() }}</th>
                                        <td>{{ $permission->name }}</td>
                                        <td>{{ $permission->module->name }}</td>
                                        <td>{{ $permission->slug_name }}</td>
                                        <td>{{ $permission->description }}</td>
                                        <td class="text-center">{!! $permission->status == 1 ? '<span class="badge badge-success">Active</span>':'<span class="badge badge-danger">Inactive</span>' !!}</td>
                                        <td class="text-center">
                                            <a class="btn btn-info btn-sm" title="Edit" href="{{ route('permissions.edit',$permission->id) }}"> <i class="fa fa-edit"></i> </a>
                                            <button type="button" data-toggle="modal" onclick="deleteData({{ $permission->id }})" data-target="#DeleteModal" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $permissions])
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
            var url = '{{ route("permissions.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }
        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>


    @include('admin.includes.delete_confirm')


    @endsection