
@extends('admin.master')
@section('title', ' - Brand List')

@section('content')

    <main class="app-content">
        <!-- breadcrumb -->
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Brand List</h1>
                <p>Brand information</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <div class="tile">
                    @if(hasPermission('products.create'))
                        <a href="{{ route('brands.create') }}" class="btn btn-primary" style="float: right;">
                            <i class="fa fa-plus"></i> Add Brand
                        </a>
                    @endif
                    <h3 class="tile-title"><i class="fa fa-list"></i> Brand List </h3>

                    <!-- filter -->
                    <div class="row">
                        <div class="col-sm-8 mx-auto">
                            <form action="" method="get">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="height: 33px !important;">Search Brand</span>
                                    </div>
                                    <input class="form-control form-control-md" name="name" placeholder="Search anything.........." value="{{ request('name') }}">
                                    <div class="input-group-append">
                                        <button style="cursor: pointer" type="submit" class="input-group-text btn-sm"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                    <div class="tile-body table-responsive" style="min-height: 740px !important;">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                                <tr>
                                    <th width="10%">Sl.</th>
                                    <th>Brand Name</th>
                                    <th>Brand Code</th>
                                    <th class="text-center">Status</th>
                                    <th width="15%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($brands as $key => $brand)
                                    <tr>
                                        <td>{{ $key + $brands->firstItem() }}</td>
                                        <td>{{ $brand->name }}</td>
                                        <td>{{ $brand->code }}</td>
                                        <td class="text-center">{!! $brand->status == 1 ? '<span class="badge badge-success">Active</span>':'<span class="badge badge-danger">Inactive</span>' !!}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                @include('admin.includes.user-log', ['data' => $brand])

                                                @if(hasPermission('products.edit'))
                                                    <a class="btn btn-success btn-sm" title="Edit" href="{{ route('brands.edit', $brand->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if (hasPermission('products.destroy'))
                                                    <button type="button" data-toggle="modal" onclick="deleteData({{ $brand->id }})" data-target="#DeleteModal" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $brands])
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
            var url = '{{ route("brands.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>
@stop
