@extends('admin.master')
@section('title', ' - Company Edit')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Edit Company</h1>
                <p>Edit Company Form</p>
            </div>

            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Companies</li>
                <li class="breadcrumb-item"><a href="#">Edit Company</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <form class="form-horizontal" method="POST" action="{{ route('companies.update',$company->id) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="tile">
                        <h3 class="tile-title">Company Edit</h3>
                        <div class="tile-body" style="height: 740px !important;">
                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Company Name</label>
                                <div class="col-md-8">
                                    <input name="name" value="{{ $company->name }}" class="form-control" type="text" placeholder="Enter company name">
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Company Phone</label>
                                <div class="col-md-8">
                                    <input name="phone" value="{{ $company->phone }}" class="form-control" type="text" placeholder="Enter company phone">
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Company Email</label>
                                <div class="col-md-8">
                                    <input name="email" value="{{ $company->email }}" class="form-control" type="email" placeholder="Enter company email address">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3">Company Website</label>
                                <div class="col-md-8">
                                    <input name="website" value="{{ $company->website }}" class="form-control" type="text" placeholder="Enter company website address">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3">Company Address</label>
                                <div class="col-md-8">
                                    <textarea name="address" class="form-control" rows="4" placeholder="Enter company address">{{ $company->address }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Company Logo</label>
                                <div class="col-md-8">
                                    <input name="company_logo" class="form-control" type="file">
                                    <br>
                                    <img src="{{ asset($company->company_logo) }}" alt="" srcset="" width="50" height="50">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3">Account Linked</label>
                                <div class="col-md-8">
                                    <select name="account_linked" class="form-control">
                                        <option value="0" {{ $company->account_linked == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ $company->account_linked == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-11">
                                    <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
