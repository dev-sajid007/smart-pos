@extends('admin.master')
@section('title', ' - Permission Modules')

@section('content')

    <main class="app-content">
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
                    <a href="{{ route('modules.create') }}" class="btn btn-primary" style="float: right;">
                        <i class="fa fa-plus"></i> Add Module
                    </a>
                    <h3 class="tile-title">Module List</h3>
                    <div class="tile-body table-responsive" style="min-height: 440px">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                                <tr>
                                    <th>Module Name</th>
                                    <th>Company</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($modules as $module)
                                    <tr>
                                        <td>{{ $module->name }}</td>
                                        <td>{{ $module->company->name }}</td>
                                        <td>{{ $module->description }}</td>
                                        <td>
                                            {!!
                                                $module->no_general == 1
                                                ? '<span class="badge badge-success">Active</span>'
                                                :'<span class="badge badge-danger">Inactive</span>'
                                            !!}
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-info btn-sm" title="Edit"
                                               href="{{ route('modules.edit', $module->id) }}"> <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button" data-toggle="modal" onclick="deleteData({{ $module->id }})" data-target="#DeleteModal" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
            var url = '{{ route("modules.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }
        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>

    @include('admin.includes.delete_confirm')

@endsection