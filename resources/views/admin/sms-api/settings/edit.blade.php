@extends('admin.master')
@section('title', ' - Sms Api Setting')


@section('content')
    <main class="app-content">
        <div class="app-title">
            <div class="div">
                <h1><i class="fa fa-laptop"></i> Sms Api</h1>
                <p>Sms Api</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="#">Sms Api Setting</a></li>
            </ul>
        </div>


        <div class="tile mb-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header">
                        <h2 class="mb-3 line-head" id="navs">Sms Api Edit
                            <a href="{{ route('sms-apis.index') }}" role="button" class="btn btn-primary pull-right text-light">
                                <i class="fa fa-list" aria-hidden="true"></i> List
                            </a>
                        </h2>
                    </div>
                </div>
            </div>

            <div class="alert-message"></div>

            <div class="row" style="margin-bottom: 2rem;">
                <div class="col-12">

                    @include('partials._alert_message'
)
                    <form method="post" action="{{ route('sms-apis.update', $smsApi->id) }}">
                        @csrf @method('PUT')

                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <div class="form-group row">
                                    <label class="col-md-3 text-right">API Title</label>
                                    <div class="col-md-9">
                                        <input type="text" value="{{ old('api_title', $smsApi->api_title) }}" name="api_title" class="form-control" placeholder="Actual name of api url">
                                        <div class="text-danger">{{ $errors->api_title ? $errors->first('api_title') : '' }}</div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 text-right">API Url</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" cols="5" style="height: 130px" placeholder="mobile_number_field=DYNAMIC_MOBILE_NUMBERS&message_field=DYNAMIC_MESSAGE" name="api_url">{{ old('api_url', $smsApi->api_url) }}</textarea>
                                        <div class="text-danger">{{ $errors->api_url ? $errors->first('api_url') : '' }}</div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 text-right">Balance Url</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" cols="3" style="min-height: 70px" placeholder="full sms balance url" name="get_sms_balance_rul">{{ old('get_sms_balance_rul', $smsApi->get_sms_balance_rul) }}</textarea>
                                        <div class="text-danger">{{ $errors->get_sms_balance_rul ? $errors->first('get_sms_balance_rul') : '' }}</div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 text-right">Status</label>
                                    <div class="col-md-3 text-right">
                                        <select name="status" id="status" class="form-control">
                                            <option value="1" {{ old('status', $smsApi->status) == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('status', $smsApi->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        <div class="text-danger">{{ $errors->status ? $errors->first('status'):'' }}</div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="offset-sm-3 col-md-9">
                                        <button class="btn btn-primary pull-right"><i class="fa fa-edit"></i> Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('footer-script')

@endsection
