
@extends('admin.master')
@section('title', ' - Account Edit')
@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Account</h1>
                <p>Gl Account</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Account</li>
                <li class="breadcrumb-item"><a href="#">Gl Account</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Edit Account</h3>
                    <div class="tile-body" style="min-height: 440px">
                        <form class="form-horizontal" method="post" action="{{ route('gl-accounts.update', $account->id) }}">
                            @csrf @method('PATCH')


                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Account Name</label>
                                <div class="col-md-8">
                                    <input name="name" value="{{ old('name', $account->name) }}" class="form-control" type="text" placeholder="Account Name">
                                </div>
                            </div>


                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update Account</button>
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
