@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-th-list"></i> Warehouse</h1>
                <p>Manage Warehouse</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @if(Session::get('message'))
                    <div class="alert alert-success">
                        {{ Session::get('message') }}
                    </div>
                @endif

                <div class="tile">
                    <a href="{{ route('warehouses.create') }}" class="btn btn-primary" style="float: right;">
                        <i class="fa fa-plus"></i>Add Warehouse
                    </a>
                    <h3 class="tile-title">Warehouse List </h3>

                    <div class="tile-body table-responsive" style="min-height: 740px !important;">
                        <table class="table table-hover table-bordered" id="">
                            <thead>
                                <th>Sl.</th>
                                <th>Warehouse Name</th>
                                <th>Action</th>
                            </thead>

                            <tbody>
                                @foreach($warehouses as $key => $warehouse)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $warehouse->name }}</td>
                                        <td>
                                            <div class="btn-group btn-corner">
                                                <a class="btn btn-info btn-sm" title="Edit" href="{{ route('warehouses.edit', $warehouse->id) }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <a title="Delete" class="btn btn-danger btn-sm" href="#"
                                                   data-toggle="modal" onclick="deleteData({{ $warehouse->id }})"
                                                   data-target="#DeleteModal"><i class="fa fa-trash"></i></a>
                                            </div>
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

@endsection


@section('js')
    <script>
        function deleteData(id) {
            var id = id;
            var url = '{{ route("warehouses.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>
@endsection
