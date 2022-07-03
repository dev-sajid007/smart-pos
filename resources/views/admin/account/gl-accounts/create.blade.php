@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Account Create</h1>
            </div>
            <a href="{{ route('gl-accounts.index') }}" class="btn btn-primary pull-right" style="float: right;"><i class="fa fa-eye"></i> View Account</a>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">

                    <div class="tile-body" style="min-height: 640px !important;">
                        <form class="form-horizontal" method="post" action="{{ route('gl-accounts.store') }}">
                            @csrf



                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Account Name</label>
                                <div class="col-md-8">
                                    <input name="name" class="form-control" type="text" value="{{ old('name') }}" placeholder="Account Name">
                                </div>
                            </div>


                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Save</button>
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
