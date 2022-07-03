
@extends('admin.master')
@section('title', ' - Unit List')

@section('content')
    <main class="app-content">
        <!-- breadcrumb -->
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Product Unit</h1>
                <p>Create Product Unit Form</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Product Units</li>
                <li class="breadcrumb-item"><a href="#">Add Product Unit</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <div class="tile">
                    <a href="{{route('units.index')}}" class="btn btn-primary pull-right" style="float: right;">
                        <i class="fa fa-list"></i> Unit List
                    </a>
                    <h3 class="tile-title">Add New Unit</h3>

                    <div class="tile-body" style="min-height: 740px !important;">
                        <form class="form-horizontal" method="POST" action="{{ route('units.store') }}">
                            @csrf

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Unit Name</label>
                                <div class="col-md-8">
                                    <input name="name" value="{{ old('name') }}" class="form-control" type="text" placeholder="Name">
                                    <div class="text-danger">
                                        {{ $errors->has('name') ? $errors->first('name'):'' }}
                                    </div>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="control-label col-md-3">Description</label>
                                <div class="col-md-8">
                                    <textarea name="description" style="height: 70px" class="form-control" rows="5" type="text" placeholder="Description">{{ old('description') }}</textarea>
                                    <div class="text-danger">
                                        {{ $errors->has('description') ? $errors->first('description'):'' }}
                                    </div>
                                </div>
                            </div>


                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Status</label>
                                <div class="col-md-8">
                                    <select name="status" id="" class="form-control">
                                        <option value="1" {{ old('status') == 1 ? 'active' : '' }}>Active</option>
                                        <option value="0" {{ old('status') == 0 ? 'active' : '' }}>Inactive</option>
                                    </select>
                                    <div class="text-danger">
                                        {{ $errors->has('status') ? $errors->first('status'):'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit" type="submit">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i> Save Unit
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
@endsection

@section('footer-script')

    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>

    <script type="text/javascript">
        $('.select2').select2();
    </script>
@endsection
