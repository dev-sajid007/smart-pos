@extends('admin.master')
@section('title', ' - Company Create')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Add Company</h1>
                <p>Create Company Form</p>
            </div>

            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Companies</li>
                <li class="breadcrumb-item"><a href="#">Add Company</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <form class="form-horizontal" method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="tile">
                        <a href="{{route('companies.index')}}" class="btn btn-primary" style="float: right;"><i class="fa fa-eye"></i>View Company</a>
                        <h3 class="tile-title">Company Add</h3>

                        <div class="tile-body" style="height: 740px !important;">
                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Company Name</label>
                                <div class="col-md-8">
                                    <input name="name" value="{{ old('name') }}" class="form-control" type="text" placeholder="Enter company name">
                                    <div class="text-danger">{{ $errors->has('name') ? $errors->first('name'):'' }}</div>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Company Phone</label>
                                <div class="col-md-8">
                                    <input name="phone" value="{{ old('phone') }}" class="form-control" type="text" placeholder="Enter company phone">
                                    <div class="text-danger">{{ $errors->has('phone') ? $errors->first('phone'):'' }}</div>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Company Email</label>
                                <div class="col-md-8">
                                    <input name="email" value="{{ old('email') }}" class="form-control" type="email" placeholder="Enter company email address">
                                    <div class="text-danger">{{ $errors->has('email') ? $errors->first('email'):'' }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3">Company Website</label>
                                <div class="col-md-8">
                                    <input name="website" value="{{ old('website') }}" class="form-control" type="text" placeholder="Enter company website address">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3">Company Address</label>
                                <div class="col-md-8">
                                    <textarea name="address" class="form-control" rows="4" placeholder="Enter company address">{{ old('address') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Company Logo</label>
                                <div class="col-md-8">
                                    <input name="company_logo" class="form-control" type="file">
                                    <div class="text-danger">{{ $errors->has('company_logo') ? $errors->first('company_logo'):'' }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3">Account Linked</label>
                                <div class="col-md-8">
                                    <select name="account_linked" class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-11">
                                    <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Add</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
