
@extends('admin.master')
@section('title', ' - Group List')

@section('content')

    <main class="app-content">
        <!-- breadcrumb -->
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Product group Information</h1>
                <p>Product Group information</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Product Group Information</li>
                <li class="breadcrumb-item active"><a href="#">Product Group Information Table</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">

                <div class="tile">
                    @if(hasPermission('products.create'))
                        <a href="{{ route('product-groups.create') }}" class="btn btn-primary" style="float: right;">
                            <i class="fa fa-plus"></i> Add Group
                        </a>
                    @endif
                    <h3 class="tile-title"><i class="fa fa-list"></i> Group List </h3>

                    <div class="tile-body table-responsive" style="min-height: 440px">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                                <tr>
                                    <th width="10%">Sl.</th>
                                    <th>Group Name</th>
                                    <th>Group Code</th>
                                    <th class="text-center">Status</th>
                                    <th width="15%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($productGroups as $key => $productGroup)
                                    <tr>
                                        <td>{{ $key + $productGroups->firstItem() }}</td>
                                        <td>{{ $productGroup->name }}</td>
                                        <td>{{ $productGroup->code }}</td>
                                        <td class="text-center">{!! $productGroup->status == 1 ? '<span class="badge badge-success">Active</span>':'<span class="badge badge-danger">Inactive</span>' !!}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                @if(hasPermission('products.edit'))
                                                    <a class="btn btn-info btn-sm" title="Edit" href="{{ route('product-groups.edit', $productGroup->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if (hasPermission('products.destroy'))
                                                    <button type="button" data-toggle="modal" onclick="deleteData({{ $productGroup->id }})" data-target="#DeleteModal" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $productGroups])
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
            var url = '{{ route("product-groups.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>
@stop
