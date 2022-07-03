@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Account Create</h1>
            </div>
            <a href="{{ route('accounts.index') }}" class="btn btn-primary pull-right" style="float: right;"><i class="fa fa-eye"></i> View Account</a>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">

                    <div class="tile-body" style="min-height: 740px !important;">
                        <form class="form-horizontal" method="post" action="{{ route('accounts.store') }}">
                            @csrf

                            @include('partials._alert_message')

                            <input type="hidden" name="company_id" value="{{ companyId() }}">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Account No</label>
                                <div class="col-md-8">
                                    <input name="account_no" required class="form-control" type="text" placeholder="Account No">
                                </div>
                            </div>


                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Account Name</label>
                                <div class="col-md-8">
                                    <input name="account_name" required class="form-control" type="text" placeholder="Account Name">
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Branch Name</label>
                                <div class="col-md-8">
                                    <input name="branch_name" required class="form-control" type="text" placeholder="Branch Name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3">Opening Balance</label>
                                <div class="col-md-8">
                                    <input name="opening_balance" onkeypress="return event.charCode >= 46 && event.charCode <= 57" class="form-control" type="text" placeholder="Opening Balance">
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Status</label>
                                <div class="col-md-8">
                                    <div class="animated-radio-button">
                                        <label>
                                            <input type="radio" value="1" name="status" checked="">
                                            <span class="label-text">Active</span>
                                        </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" value="0" name="status"><span class="label-text">Inactive</span>
                                        </label>
                                    </div>
                                    <hr>
                                    <div class="animated-checkbox">
                                        <label>
                                            <input type="checkbox" value="1" name="default_account">
                                            <span  class="label-text">Make This Account Default</span>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i>Add Account
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
