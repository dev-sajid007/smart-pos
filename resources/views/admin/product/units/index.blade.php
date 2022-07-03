@extends('admin.master')
@section('title', ' - Unit List')

@section('content')
    <main class="app-content">
        <!-- breadcrumb -->
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Product Unit Information</h1>
                <p>Product Unit information</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Product Unit Information</li>
                <li class="breadcrumb-item active"><a href="#">Product Unit Information Table</a></li>
            </ul>
        </div>


        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <div class="tile">
                    @if(hasPermission('products.create'))
                        <a href="{{ route('units.create') }}" class="btn btn-primary" style="float: right;">
                            <i class="fa fa-plus"></i> Add Unit
                        </a>
                    @endif

                    <h3 class="tile-title"><i class="fa fa-list"></i> Unit List</h3>

                    <div class="tile-body table-responsive" style="min-height: 740px !important;">
                        <table class="table table-hover table-bordered table-sm" id="">
                            <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th>Unit Name</th>
                                    <th>Description</th>
                                    <th class="text-center">Status</th>
                                    <th width="10%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($productUnits as $key => $productUnit)
                                    <tr>
                                        <td>{{ $key + $productUnits->firstItem() }}</td>
                                        <td>{{ ucfirst($productUnit->name) }}</td>
                                        <td>{{ $productUnit->description }}</td>
                                        <td class="text-center">{!! $productUnit->status == 1 ? '<span class="badge badge-success">Active</span>':'<span class="badge badge-danger">Inactive</span>' !!}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                @include('admin.includes.user-log', ['data' => $productUnit])

                                                @if(hasPermission('products.edit'))
                                                    <a class="btn btn-success btn-sm" title="Edit" href="{{ route('units.edit', $productUnit->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if (hasPermission('products.destroy'))
                                                    <button type="button" data-toggle="modal" onclick="deleteData({{ $productUnit->id }})" data-target="#DeleteModal" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $productUnits])
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
            var url = '{{ route("units.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>
@stop
