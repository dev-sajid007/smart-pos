@extends('admin.master')
@section('title', ' - Payment Method Create')
@section('content')

    <main class="app-content">
        <div class="app-title">
            <div class="row" style="width: 100%">
                <div class="col-sm-12">
                    <h3 style="display: inline-block">Add Payment Method</h3>
                    <a href="{{route('payments-method.store')}}" class="btn btn-primary" style="float: right;">
                        <i class="fa fa-eye"></i> View Payment Method
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body" style="min-height: 740px !important;">
                        <form class="form-horizontal" method="post" action="{{route('payments-method.store')}}">
                            @csrf


                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Payment Method Name</label>
                                <div class="col-md-8">
                                    <input name="method_name" class="form-control" value="{{ old('method_name') }}" type="text" placeholder="Method Name">
                                    <div class="text-danger">
                                        {{ $errors->has('method_name') ? $errors->first('method_name'):'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Status</label>
                                <div class="col-md-8">
                                    <div class="animated-radio-button">
                                        <label>
                                            <input type="radio" value="1" name="status" checked=""><span class="label-text">Active</span>
                                        </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" value="0" name="status"><span class="label-text">Inactive</span>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i> Add Payment Method
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

@endsection
