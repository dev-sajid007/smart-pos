
@extends('admin.master')
@section('content')

    <main class="app-content">

        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <div class="tile">
                    <h3 class="tile-title">Add New Module
                        <a href="{{route('roles.index')}}" class="btn btn-primary pull-right" style="float: right;">
                            <i class="fa fa-eye"></i> View Role
                        </a>
                    </h3>

                    <div class="tile-body" style="min-height: 440px">
                        <form class="form-horizontal" method="POST" action="{{ route('modules.store') }}">
                            @csrf

                            @if($companies != null)
                                <div class="form-group row">
                                    <label class="control-label col-md-3">Company</label>
                                    <div class="col-md-8">
                                        <select name="fk_company_id" class="form-control select2">
                                            @foreach ($companies as $id => $company)
                                                <option value="{{ $id }}">{{ $company }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif


                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Name</label>
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


                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit" type="submit">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i> Add Module
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
    <script src="{{asset('jq/select2Loads.js')}}"></script>
@stop