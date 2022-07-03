@extends('admin.master')

@section('content')

    <main class="app-content">
        <div class="app-title">
            <div style="width: 100%">
                <h1><i class="fa fa-plus"></i> Fund Transfer Create <a class="btn btn sm btn-success float-right" href="{{ route('fund-transfers.index') }}"><i class="fa fa-list"></i> List</a></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <form class="form-horizontal" action="{{ route('fund-transfers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="tile">
                        <h3 class="tile-title">Fund Transfer</h3>
                        <div class="tile-body" style="height: 740px !important;">

                                <div class="form-group row add_asterisk">
                                    <label class="control-label col-md-3">From Account</label>
                                    <div class="col-md-8">
                                        <select name="from_account_id" required class="form-control from-account select2">
                                            <option value="">Select</option>
                                            @foreach ($accounts as $id => $account)
                                                <option data-total-amount="{{ $account->total_amount ?? 0 }}" value="{{ $account->id }}" {{ $account->id == old('from_account_id') ? 'selected' : '' }}>{{ $account->account_name }} ( {{ $account->total_amount ?? 0 }} )</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">{{ $errors->has('from_account_id') ? $errors->first('from_account_id') : '' }}</div>
                                    </div>
                                </div>


                            <div class="form-group row add_asterisk">
                                <label for="" class="control-label col-md-3">To Account</label>
                                <div class="col-md-8">
                                    <select name="to_account_id" id="" class="form-control to-account select2">
                                        <option value="">Select</option>
                                        @foreach ($accounts as $id => $account)
                                            <option value="{{ $account->id }}" {{ $account->id == old('to_account_id') ? 'selected' : '' }}>{{ $account->account_name }} ( {{ $account->total_amount ?? 0 }} )</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">{{ $errors->has('to_account_id') ? $errors->first('to_account_id') : '' }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3">Amount</label>
                                <div class="col-md-8">
                                    <input name="amount" class="form-control select-amount" type="text" onkeypress="return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57" placeholder="Transfer Amount" value="{{ old('amount') }}">
                                    <div class="text-danger">{{ $errors->has('amount') ? $errors->first('amount'):'' }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3">Comment</label>
                                <div class="col-md-8">
                                    <textarea name="comment" class="form-control" rows="3" style="height: 80px !important;" placeholder="Type your comment/note">{{ old('comment') ?: '' }}</textarea>
                                    <div class="text-danger">{{ $errors->has('comment') ? $errors->first('comment'):'' }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3">Reference no.</label>
                                <div class="col-md-8">
                                    <input name="reference_no" class="form-control" type="text" placeholder="Reference Number" value="{{ old('reference_no') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3">Reference Image</label>
                                <div class="col-md-8">
                                    <input name="image" class="form-control" type="file" placeholder="Reference Image" value="">
                                    <div class="text-danger">{{ $errors->has('image') ? $errors->first('image') : '' }}</div>
                                </div>
                            </div>
                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-11">
                                        <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-fw fa-lg fa-save"></i> Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </main>
@endsection


@section('js')
    <script type="text/javascript" src="{{ asset('assets/custom_js/fund-transfer-create.js') }}"></script>
@stop
