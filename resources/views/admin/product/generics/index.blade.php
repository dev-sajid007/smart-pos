
@extends('admin.master')
@section('title', ' - Generic List')

@section('content')

    <main class="app-content">
        <!-- breadcrumb -->
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Generic List</h1>
                <p>Generic information</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <div class="tile">
                    @if(hasPermission('products.create'))
                        <a href="{{ route('generics.create') }}" class="btn btn-primary" style="float: right;">
                            <i class="fa fa-plus"></i> Add Generic
                        </a>
                    @endif
                    <h3 class="tile-title"><i class="fa fa-list"></i> Generic List </h3>

                    <div class="tile-body table-responsive" style="min-height: 740px !important;">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                                <tr>
                                    <th width="10%">Sl.</th>
                                    <th>Generic Name</th>
                                    <th class="text-center">Status</th>
                                    <th width="15%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($generics as $key => $generic)
                                    <tr>
                                        <td>{{ $key + $generics->firstItem() }}</td>
                                        <td>{{ $generic->name }}</td>
                                        <td class="text-center">{!! $generic->status == 1 ? '<span class="badge badge-success">Active</span>':'<span class="badge badge-danger">Inactive</span>' !!}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                @include('admin.includes.user-log', ['data' => $generic])

                                                @if(hasPermission('products.edit'))
                                                    <a class="btn btn-success btn-sm" title="Edit" href="{{ route('generics.edit', $generic->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if (hasPermission('products.destroy'))
                                                    <button type="button" data-toggle="modal" onclick="deleteData({{ $generic->id }})" data-target="#DeleteModal" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $generics])
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('admin.includes.delete_confirm')
@endsection

@section('footer-script')

    <script type="text/javascript">
        function deleteData(id) {
            var id = id;
            var url = '{{ route("generics.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>
@stop
