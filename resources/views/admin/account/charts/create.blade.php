@extends('admin.master')
@section('title', ' - Char of account create')
@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">

                    <a href="{{ route('accounts-charts.index') }}" class="btn btn-primary pull-right"><i
                                class="fa fa-eye"></i> Chart of Accounts</a>
                    <h3><i class="fa fa-edit"></i> Chart of Accounts Create</h3><br>

                    <div class="tile-body">
                        <form class="form-horizontal" method="post" action="{{ route('accounts-charts.store') }}">
                            @csrf

                            <div class="form-group row  add_asterisk">
                                <label class="control-label col-md-2">Gl Account</label>
                                <div class="col-md-6">
                                    <select name="gl_account_id" class="form-control select2" required type="text">
                                        @foreach($gl_accounts as $id => $account)
                                            <option value="{{ $id }}" {{ $id == old('gl_account_id') ? 'selected' : '' }}>{{ $account }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-2">Head Type</label>
                                <div class="col-md-6">
                                    <select name="head_type" class="form-control" type="text">
                                        <option value="">--Select Head Type--</option>
                                        <option value="0">Income</option>
                                        <option value="1">Expense</option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-2">Head Name</label>
                                <div class="col-md-6">
                                    <input name="head_name" class="form-control" type="text" placeholder="Head Name">
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-2">Status</label>
                                <div class="col-md-6">
                                    <div class="animated-radio-button">
                                        <label>
                                            <input type="radio" value="1" name="status" checked="">
                                            <span class="label-text">Active</span>
                                        </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" value="0" name="status">
                                            <span class="label-text">Inactive</span>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-8">
                                        <button class="btn btn-primary pull-right" type="submit">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i>Add Chart of Account
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
