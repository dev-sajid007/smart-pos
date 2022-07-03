@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-th-list"></i> Group Information</h1>
                <p>Group information </p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Group Information</li>
                <li class="breadcrumb-item active"><a href="#">Group Information Table</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if(Session::get('message'))
                    <div class="alert alert-success">
                        {{ Session::get('message') }}
                    </div>
                @endif

                <div class="tile">
                    @if(hasPermission('groups.create'))
                    <a href="{{ url('groups/get_import') }}" class="btn btn-primary" style="float: right;"><i
                                class="fa fa-plus"></i>Add Group</a>
                    @endif
                    <h3 class="tile-title">Group List </h3>
                    <div class="tile-body table-responsive">
                        <table class="table table-hover table-bordered" id="">
                            <thead>
                            <tr>
                                <th>Group Code</th>
                                <th>Name</th>
                                <th>Total Members</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($groups as $group)
                                <tr>
                                    <td>{{ $group->group_code }}</td>
                                    <td>{{ $group->name }}</td>
                                    <td>{{ $group->total_members() }}</td>
                                    <td>
                                        <a href="{{ url('groups/show', $group->id) }}" class="btn btn-primary btn-sm"><i
                                                    class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            @include('admin.includes.delete_confirm')

                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $groups])
                    </div>
                </div>
            </div>
        </div>
    </main>


    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>

    <script>
        function formSubmit(id) {
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this data !",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel plz!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $('#deleteForm_' + id).submit();
                    swal("Deleted!", "Your data has been deleted.", "success");
                } else {
                    swal("Cancelled", "Your data is safe :)", "error");
                }
            });
        }
    </script>




@endsection