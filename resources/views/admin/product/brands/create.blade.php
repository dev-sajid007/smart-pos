@extends('admin.master')
@section('title', ' - Brand Create')

@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Brand Create</h1>
                <p>Create Brand Form</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Brands</li>
                <li class="breadcrumb-item"><a href="#">Add Brand</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">

                @include('partials._alert_message')

                <div class="tile">
                    <a href="{{ route('brands.index') }}" class="btn btn-primary pull-right" style="float: right;">
                        <i class="fa fa-eye"></i> View Brands
                    </a>
                    <h3 class="tile-title">Add New Brand</h3>

                    <div class="tile-body" style="min-height: 740px !important;">
                        <form class="form-horizontal" method="post" action="{{ route('brands.store') }}">
                            @csrf

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Brand Name</label>
                                <div class="col-md-8">
                                    <input name="name" required class="form-control" type="text" placeholder="Brand Name">
                                </div>
                            </div>


                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i>Add Brand
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
