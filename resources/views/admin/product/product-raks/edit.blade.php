@extends('admin.master')
@section('title', ' - Product Rak Edit')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Product Rak Update</h1>
                <p>Product Rak Edit Form</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Product Raks</li>
                <li class="breadcrumb-item"><a href="#">Add Product Rak</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="POST" action="{{ route('product-raks.update', $productRak->id) }}">
                    @csrf @method('PUT')

                    <div class="tile">
                        <h3 class="tile-title">Update Product Rak</h3>

                        <div class="tile-body" style="min-height:740px !important;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group row add_asterisk">
                                        <label class="control-label col-md-3">Product Rak Name</label>
                                        <div class="col-md-9">
                                            <input name="name" value="{{ old('name', $productRak->name) }}" class="form-control" type="text" placeholder="Product Rak Name">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button class="btn btn-primary pull-right" type="submit">
                                        <i class="fa fa-fw fa-lg fa-check-circle"></i> Update Product Rak
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
