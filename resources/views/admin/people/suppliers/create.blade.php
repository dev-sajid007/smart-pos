@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <div class="tile">
                    <a href="{{ route('suppliers.index') }}" class="btn btn-primary pull-right" style="float: right;">
                        <i  class="fa fa-eye"></i> Supplier List
                    </a>

                    <h3 class="tile-title">Add New Supplier</h3>
                    <hr>

                    <div class="tile-body">
                        <form class="form-horizontal" method="POST" action="{{ route('suppliers.store') }}">
                            @csrf

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-2">Name</label>
                                <div class="col-md-6">
                                    <input name="name" value="{{ old('name') }}" class="form-control" type="text" placeholder="Name">
                                    <div class="text-danger">
                                        {{ $errors->has('name') ? $errors->first('name'):'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-2">Company Name</label>
                                <div class="col-md-6">
                                    <input name="company_name" value="{{ old('company_name') }}" class="form-control" type="text" placeholder="Company Name">
                                    <div class="text-danger">
                                        {{ $errors->has('company_name') ? $errors->first('company_name'):'' }}
                                    </div>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="control-label col-md-2">Email</label>
                                <div class="col-md-6">
                                    <input name="email" value="{{ old('email') }}" class="form-control" type="email" placeholder="Email">
                                    <div class="text-danger">
                                        {{ $errors->has('email') ? $errors->first('email'):'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-2">Phone</label>
                                <div class="col-md-6">
                                    <input name="phone" value="{{ old('phone') }}" class="form-control" type="number" placeholder="Phone">
                                    <div class="text-danger">
                                        {{ $errors->has('phone') ? $errors->first('phone'):'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-2">Address</label>
                                <div class="col-md-6">
                                    <textarea name="address" class="form-control h-100" type="text" placeholder="Address">{{ old('address') }}</textarea>
                                    <div class="text-danger">
                                        {{ $errors->has('address') ? $errors->first('address'):'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-2">Opening Payable Due</label>
                                <div class="col-md-6">
                                    <input name="opening_due" value="{{ old('opening_due') ?? 0 }}" class="form-control" placeholder="Opening Due">
                                    <div class="text-danger">
                                        {{ $errors->has('opening_due') ? $errors->first('opening_due'):'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <button class="btn btn-primary pull-right" type="submit">
                                        <i class="fa fa-fw fa-lg fa-check-circle"></i>Save Supplier
                                    </button>
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
