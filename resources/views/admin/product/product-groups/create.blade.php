@extends('admin.master')
@section('title', ' - Group Create')

@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Group Create</h1>
                <p>Create Group Form</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Groups</li>
                <li class="breadcrumb-item"><a href="#">Add Group</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">

                @include('partials._alert_message')

                <div class="tile">
                    <a href="{{ route('product-groups.index') }}" class="btn btn-primary pull-right" style="float: right;">
                        <i class="fa fa-eye"></i> View Groups
                    </a>
                    <h3 class="tile-title">Add New Group</h3>

                    <div class="tile-body" style="min-height: 440px">
                        <form class="form-horizontal" method="post" action="{{ route('product-groups.store') }}">
                            @csrf

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Group Name</label>
                                <div class="col-md-8">
                                    <input name="name" required class="form-control" type="text" placeholder="Group Name">
                                </div>
                            </div>


                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i>Add Group
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
