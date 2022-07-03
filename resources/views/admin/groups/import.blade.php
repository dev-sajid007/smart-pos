@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> SMS</h1>
                <p>Import Group Form</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Groups</li>
                <li class="breadcrumb-item"><a href="#">Import Groups</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">

                @if(Session::get('message'))
                    <div class="alert alert-success">
                        {{ Session::get('message') }}
                    </div>
                @endif

                @if(Session::get('error'))
                    <div class="alert alert-danger">
                        {{ Session::get('error') }}
                    </div>
                @endif


                @include('partials._alert_message')

                <div class="tile">
                    <a href="{{ url('groups/download_sample') }}" class="btn btn-primary pull-right"><i
                                class="fa fa-download"></i>Download Sample</a>
                    <h3 class="tile-title">Import Groups</h3>
                    <div class="tile-body" style="min-height: 440px"                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    >
                        <form class="form-horizontal" method="POST" action="{{ url('groups/parse_import') }}"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Name</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                           placeholder="Group Name">
                                    <div class="text-danger">
                                        {{ $errors->has('name') ? $errors->first('name'):'' }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Upload CSV file</label>
                                <div class="col-md-8">
                                    <input name="group_csv" type="file" class="form-control">
                                    <div class="text-danger">
                                        {{ $errors->has('group_csv') ? $errors->first('group_csv'):'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit" type="submit"><i
                                                    class="fa fa-fw fa-lg fa-check-circle"></i>Add Group
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

    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>


    <script type="text/javascript">
        $('.select2').select2();
    </script>
@endsection