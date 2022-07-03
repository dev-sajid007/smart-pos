@extends('admin.master')
@section('title', ' - Category Create')

@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Category Create</h1>
                <p>Create Category Form</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Categories</li>
                <li class="breadcrumb-item"><a href="#">Add Category</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">

                @include('partials._alert_message')

                <div class="tile">
                    <a href="{{ route('categories.index') }}" class="btn btn-primary pull-right" style="float: right;">
                        <i class="fa fa-eye"></i> View Categories
                    </a>
                    <h3 class="tile-title">Add New Category</h3>

                    <div class="tile-body" style="min-height: 740px !important;">
                        <form class="form-horizontal" method="post" action="{{ route('categories.store') }}">
                            @csrf


                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Category Name</label>
                                <div class="col-md-8">
                                    <input name="category_name" class="form-control" type="text" placeholder="Category Name">
                                </div>
                            </div>


                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i> Save Category
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
