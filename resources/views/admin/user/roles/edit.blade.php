@extends('admin.master')
@section('title', ' - Role Edit')
@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> People</h1>
                <p>Create Role Form</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Roles</li>
                <li class="breadcrumb-item"><a href="#">Add Role</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                    <div class="tile">
                        <h3 class="tile-title">Edit Role</h3>
                        <div class="tile-body" style="min-height: 440px">
                            <form class="form-horizontal" method="POST" action="{{ route('roles.update', $Role->id) }}">
                                @csrf @method("PUT")
                                <input type="hidden" name="id" value="{{ $Role->id }}">

                                <div class="form-group row add_asterisk">
                                    <label class="control-label col-md-3">Name</label>
                                    <div class="col-md-8">
                                        <input name="name" value="{{ $Role->name }}" class="form-control" type="text" placeholder="Name">
                                        <div class="text-danger">
                                            {{ $errors->has('name') ? $errors->first('name'):'' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3">Description</label>
                                    <div class="col-md-8">
                                        <textarea name="description" class="form-control" rows="5" type="text" placeholder="Description">{{ $Role->description }}</textarea>
                                        <div class="text-danger">
                                            {{ $errors->has('description') ? $errors->first('description'):'' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row add_asterisk">
                                    <label class="control-label col-md-3">Status</label>
                                    <div class="col-md-8">
                                        <select name="status" id="" class="form-control">
                                            <option value="1" {{ $Role->status == 1 ? 'selected':'' }}>Active</option>
                                            <option value="0" {{ $Role->status == 0 ? 'selected':'' }}>Inactive</option>
                                        </select>
                                        <div class="text-danger">
                                            {{ $errors->has('status') ? $errors->first('status'):'' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="tile-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary pull-right" type="submit" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update Role</button>
                                        </div>
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
    <script  src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>


    <script type="text/javascript">
      $('.select2').select2();
    </script>
@endsection