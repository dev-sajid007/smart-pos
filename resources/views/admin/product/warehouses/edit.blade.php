@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Edit Warehouse</h1>
                <p>Manage Warehouse</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <a href="{{ route('warehouses.index') }}" class="btn btn-success pull-right" style="float: right;">
                        <i class="fa fa-eye"></i> View Warehouses
                    </a>
                    <h3 class="tile-title">Edit Warehouses</h3>

                    <div class="tile-body" style="min-height: 740px !important;">
                        <form class="form-horizontal" method="post" action="{{ route('warehouses.update', $warehouse->id) }}">
                            @csrf @method('PUT')


                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Warehouse Name</label>
                                <div class="col-md-8">
                                    <input name="name" class="form-control" type="text" value="{{ old('name', $warehouse->name) }}" placeholder="Warehouse Name">
                                </div>
                            </div>

                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i>Update Warehouse
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
