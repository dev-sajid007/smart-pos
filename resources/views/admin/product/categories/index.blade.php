
@extends('admin.master')
@section('title', ' - Category List')

@section('content')

    <main class="app-content">
        <!-- breadcrumb -->
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Product category Information</h1>
                <p>Product Category information</p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Product Category Information</li>
                <li class="breadcrumb-item active"><a href="#">Product Category Information Table</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">

                <div class="tile">
                    @if(hasPermission('products.create'))
                        <a href="{{ route('categories.create') }}" class="btn btn-primary" style="float: right;">
                            <i class="fa fa-plus"></i> Add Category
                        </a>
                    @endif
                    <h3 class="tile-title"><i class="fa fa-list"></i> Category List </h3>

                    <div class="tile-body table-responsive" style="height: 740px !important;">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                                <tr>
                                    <th width="10%">Sl.</th>
                                    <th>Category Name</th>
                                    <th width="15%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($categories as $key => $category)
                                    <tr>
                                        <td>{{ $key + $categories->firstItem() }}</td>
                                        <td>{{ $category->category_name }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                @include('admin.includes.user-log', ['data' => $category])

                                                @if(hasPermission('categories.edit'))
                                                    <a class="btn btn-success btn-sm" title="Edit" href="{{ route('categories.edit', $category->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if (hasPermission('categories.destroy'))
                                                    <button type="button" data-toggle="modal" onclick="deleteData({{ $category->id }})" data-target="#DeleteModal" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $categories])
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
            var url = '{{ route("categories.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>
@stop
