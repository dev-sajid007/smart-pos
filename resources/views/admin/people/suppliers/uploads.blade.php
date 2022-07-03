@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> People</h1>
                <p>Import Supplier Form</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Suppliers</li>
                <li class="breadcrumb-item"><a href="#">Import Suppliers</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">

                @include('partials._alert_message')

                <div class="tile">
                    <div class="btn-group btn-corner pull-right">
                        <a href="{{ route('supplier.confirm.list') }}" class="btn btn-success pull-rights">
                            <i class="fa fa-list"></i> Confirm List
                        </a>
                        <a href="{{ asset('download_samples/supplier_sample_2020_10_18.csv') }}" download class="btn btn-primary pull-righst">
                            <i class="fa fa-download"></i> Download Sample
                        </a>
                    </div>


                    <h3 class="tile-title">Upload Suppliers</h3>

                    <div class="tile-body">
                        <form class="form-horizontal" method="POST" action="{{ route('upload-suppliers.store') }}"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label class="control-label col-md-3">Upload CSV file</label>
                                <div class="col-md-8">
                                    <input name="supplier_csv" type="file" class="form-control">
                                    <div class="text-danger">
                                        {{ $errors->has('supplier_csv') ? $errors->first('supplier_csv'):'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit" type="submit">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i> Save Suppliers
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
