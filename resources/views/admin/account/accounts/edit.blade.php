
@extends('admin.master')
@section('title', ' - Account Edit')
@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> System Settings</h1>
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
                <div class="tile">
                    <h3 class="tile-title">Add New Account</h3>
                    <div class="tile-body" style="min-height: 740px !important;">
                        <form class="form-horizontal" method="post" action="{{ route('accounts.update', $account->id) }}">
                            @csrf @method('PATCH')


                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Account No</label>
                                <div class="col-md-8">
                                    <input name="account_no" value="{{ $account->account_no }}" class="form-control"
                                           type="text" placeholder="Account No">
                                </div>
                            </div>


                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Account Name</label>
                                <div class="col-md-8">
                                    <input name="account_name" value="{{ $account->account_name }}" class="form-control"
                                           type="text" placeholder="Account Name">
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Branch Name</label>
                                <div class="col-md-8">
                                    <input name="branch_name" value="{{$account->branch_name}}" class="form-control"
                                           type="text" placeholder="Branch Name">
                                    <hr>

                                    @if($account->default_account==1)
                                        <div class="animated-checkbox">
                                            <label>
                                                <input type="checkbox" disabled=""><span class="label-text">Make This Account Default</span>
                                            </label>
                                        </div>
                                    @else
                                        <div class="animated-checkbox">
                                            <label>
                                                <input type="checkbox" value="1" name="default_account"
                                                    @if($account->default_account==1) checked="true" @endif>
                                                    <span class="label-text">Make This Account Default</span>
                                            </label>
                                        </div>
                                    @endif

                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3">Opening Balance</label>
                                <div class="col-md-8">
                                    <input name="opening_balance" onkeypress="return event.charCode >= 46 && event.charCode <= 57" value="{{ $openingBalance }}" class="form-control" type="text" placeholder="Opening Balance">
                                </div>
                            </div>


                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i>Update Account
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
