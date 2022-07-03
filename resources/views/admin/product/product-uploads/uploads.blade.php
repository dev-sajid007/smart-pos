@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Product</h1>
                <p>Import Product Form</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                @include('partials._alert_message')

                <div class="tile">
                    <a href="{{ asset('download_samples/product-upload-sample-2020-10-15.csv') }}" class="btn btn-primary pull-right">
                        <i class="fa fa-download"></i> Download Sample
                    </a>
                    <a href="{{ route('product-confirm-list.index') }}" class="btn btn-success pull-right" style="margin-right: 10px !important;">
                        <i class="fa fa-list"></i> Confirm List
                    </a>

                    <h3 class="tile-title">Import Products</h3>
                    <div class="tile-body" style="min-height: 740px">
                        <form class="form-horizontal" method="POST" action="{{ route('upload-products.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label class="control-label col-md-3">Upload CSV file</label>
                                <div class="col-md-8">
                                    <input name="product_csv" type="file" class="form-control">
                                    <div class="text-danger">
                                        {{ $errors->has('product_csv') ? $errors->first('product_csv') : '' }}
                                    </div>
                                </div>
                            </div>

                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12 col-md-offset-3">
                                        <button class="btn btn-primary pull-right" type="submit" type="submit"><i
                                                class="fa fa-fw fa-lg fa-check-circle"></i>Add Product
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

    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>


    <script type="text/javascript">
        $('.select2').select2();
    </script>
@endsection
