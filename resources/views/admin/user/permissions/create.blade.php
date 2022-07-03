
@extends('admin.master')
@section('title', ' - Permission')

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <div class="tile">
                    <a href="{{route('permissions.index')}}" class="btn btn-primary pull-right" style="float: right;"><i class="fa fa-eye"></i>View Permission</a>
                    <h3 class="tile-title">Add New Permission</h3>
                    <div class="tile-body">
                        <form class="form-horizontal" method="POST" action="{{ route('permissions.store') }}">
                            @csrf


                            <div class="form-group row">
                                <label class="control-label col-md-3">Module</label>
                                <div class="col-md-8">
                                    <select name="fk_module_id" class="form-control select2">
                                        @foreach ($modules as $id => $module)
                                            <option value="{{ $id }}" {{ old('fk_module_id') == $id ? 'select' : '' }} >{{ $module }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Name</label>
                                <div class="col-md-8">
                                    <input name="name" value="{{ old('name') }}" class="form-control" type="text" placeholder="Name">
                                    <div class="text-danger">
                                        {{ $errors->has('name') ? $errors->first('name'):'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Slug Name</label>
                                <div class="col-md-8">
                                    <input name="slug_name" value="{{ old('slug_name') }}" class="form-control" type="text" placeholder="Slug Name">
                                    <div class="text-danger">
                                        {{ $errors->has('slug_name') ? $errors->first('slug_name'):'' }}
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
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <div class="text-danger">
                                        {{ $errors->has('status') ? $errors->first('status'):'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Add Permission</button>
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
    <script  src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>
@endsection

