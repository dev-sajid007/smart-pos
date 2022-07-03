@extends('admin.master')
@section('title', ' - Category Edit')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Category Update</h1>
                <p>Category Edit Form</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Categories</li>
                <li class="breadcrumb-item"><a href="#">Add Category</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="POST" action="{{ route('categories.update', $category->id) }}">
                    @csrf @method('PUT')

                    <div class="tile">
                        <h3 class="tile-title">Update Category</h3>

                        <div class="tile-body" style="min-height: 740px !important;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group row add_asterisk">
                                        <label class="control-label col-md-3">Category Name</label>
                                        <div class="col-md-9">
                                            <input name="category_name" value="{{ $category->category_name }}" class="form-control" type="text" placeholder="Category Name">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button class="btn btn-primary pull-right" type="submit">
                                        <i class="fa fa-fw fa-lg fa-check-circle"></i> Update Category
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@section('footer-script')
	<script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
@endsection
