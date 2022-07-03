
@extends('admin.master')
@section('title', ' - Permissions')

@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <div class="tile">
                    <h3 class="tile-title">Edit Permission</h3>
                    <div class="tile-body">
                        <form class="form-horizontal" method="POST" action="{{ route('permissions.update', $permission->id) }}">
                            @csrf @method("PUT")

                            <div class="form-group row">
                                <label class="control-label col-md-3">Module</label>
                                <div class="col-md-8">
                                    <select name="fk_module_id" class="form-control select2">
                                        @foreach ($modules as $id => $module)
                                            <option value="{{ $id }}" {{ $permission->fk_module_id == $id ? 'select' : '' }} >{{ $module }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Name</label>
                                <div class="col-md-8">
                                    <input name="name" value="{{ $permission->name }}" class="form-control" type="text" placeholder="Name">
                                    <div class="text-danger">
                                        {{ $errors->has('name') ? $errors->first('name'):'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Slug Name</label>
                                <div class="col-md-8">
                                    <input name="slug_name" value="{{ old('slug_name', $permission->slug_name) }}" class="form-control" type="text" placeholder="Slug Name">
                                    <div class="text-danger">
                                        {{ $errors->has('slug_name') ? $errors->first('slug_name'):'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3">Description</label>
                                <div class="col-md-8">
                                    <textarea name="description" style="height: 70px" class="form-control" rows="5" type="text" placeholder="Description">{{ $permission->description }}</textarea>
                                    <div class="text-danger">
                                        {{ $errors->has('description') ? $errors->first('description'):'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Status</label>
                                <div class="col-md-8">
                                    <select name="status" id="" class="form-control">
                                        <option value="1" {{ $permission->status == 1 ? 'selected':'' }}>Active</option>
                                        <option value="0" {{ $permission->status == 0 ? 'selected':'' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="text-danger">
                                    {{ $errors->has('status') ? $errors->first('status'):'' }}
                                </div>
                            </div>

                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update Permission</button>
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