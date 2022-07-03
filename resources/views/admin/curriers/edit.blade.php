@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Courier Edit</h1>
            </div>
            <a href="{{ route('curriers.index') }}" class="btn btn-primary pull-right" style="float: right;"><i class="fa fa-eye"></i> Courier List</a>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="tile">

                    <div class="tile-body" style="min-height: 440px">
                        <form class="form-horizontal" method="post" action="{{ route('curriers.update', $currier->id) }}">
                            @csrf @method('PUT')

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Courier Name</label>
                                <div class="col-md-8">
                                    <input name="name" class="form-control" required value="{{ old('name', $currier->name) }}" type="text" placeholder="Name of currier service">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="control-label col-md-3">Mobile Number</label>
                                <div class="col-md-8">
                                    <input name="mobile" class="form-control" value="{{ old('mobile', $currier->mobile) }}" type="text" placeholder="Mobile number of currier service">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3">Address</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="address" cols="3" style="height: 70px" placeholder="Currier service address">{{ old('address', $currier->address) }}</textarea>
                                </div>
                            </div>


                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-11">
                                        <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-edit"></i> Update </button>
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
