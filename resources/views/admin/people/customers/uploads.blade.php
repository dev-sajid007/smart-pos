@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> People</h1>
                <p>Import Customer Form</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Customers</li>
                <li class="breadcrumb-item"><a href="#">Import Customers</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <div class="tile">
                    <a href="{{ asset('download_samples/customer_sample.csv') }}" download class="btn btn-primary pull-right">
                        <i class="fa fa-download"></i> Download Sample
                    </a>
                    <h3 class="tile-title">Upload Customers</h3>

                    <div class="tile-body" style="min-height: 450px">
                        <form class="form-horizontal" method="POST" action="{{ route('upload-customers.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label class="control-label col-md-3">Upload CSV file</label>
                                <div class="col-md-8">
                                    <input name="customer_csv" type="file" class="form-control">
                                    <div class="text-danger">
                                        {{ $errors->has('customer_csv') ? $errors->first('customer_csv'):'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit" type="submit">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i> Save Customers
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
