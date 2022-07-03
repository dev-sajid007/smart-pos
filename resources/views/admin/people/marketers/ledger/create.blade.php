@extends('admin.master')
@section('title', 'Marketer Create')

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')
                <div class="tile">
                    <a href="{{route('marketers.index')}}" class="btn btn-primary pull-right float-right">
                        <i class="fa fa-eye"></i> View Marketers
                    </a>
                    <h3 class="tile-title">Pay Marketers</h3>
                    <div class="tile-body">
                        <hr>
                        <div class="row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                                <form class="form-horizontal pl-5" method="POST" enctype="multipart/form-data" action="{{ route('marketers.ledger.store') }}">
                                    @csrf
                                    <!-- Name -->
                                    <input type="hidden" name="marketers_id" value="{{ $id }}">
                                    <div class="form-group row add_asterisk">
                                        <label class="control-label col-md-2">Amount</label>
                                        <div class="col-md-6">
                                            <input name="amount" value="{{ old('amount') }}" class="form-control" type="number" step="0.01" placeholder="amount">
                                            <div class="text-danger">
                                                {{ $errors->has('amount') ? $errors->first('amount'):'' }}
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Phone/Mobile -->
                                    <div class="form-group row">
                                        <label class="control-label col-md-2">Date Of paying</label>
                                        <div class="col-md-6">
                                            <input name="created_at" value="{{ old('created_at')??date('Y-m-d') }}" class="form-control"
                                                onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Phone"
                                                maxlength="11" minlength="11" type="date">
                                            <div class="text-danger">
                                                {{ $errors->has('created_at') ? $errors->first('created_at'):'' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tile-footer">
                                        <div class="row">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-2">
                                                <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-fw
                                                fa-lg fa-check-circle"></i>  Submit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
@endsection
