
@extends('admin.master')
@section('title', ' - Unit Edit')

@section('content')
    <main class="app-content">
        <!-- breadcrumb -->
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Product</h1>
                <p>Edit Product Unit Form</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Product Units</li>
                <li class="breadcrumb-item"><a href="#">Edit Product Unit</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <div class="tile">
                    <h3 class="tile-title">Edit Unit</h3>

                    <div class="tile-body" style="min-height: 740px !important;">
                        <form class="form-horizontal" method="POST" action="{{ route('units.update', $productUnit->id) }}">
                            @csrf @method("PUT")

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Unit Name</label>
                                <div class="col-md-8">
                                    <input name="name" value="{{ $productUnit->name }}" class="form-control" type="text" placeholder="Name">
                                    <div class="text-danger">
                                        {{ $errors->has('name') ? $errors->first('name'):'' }}
                                    </div>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="control-label col-md-3">Description</label>
                                <div class="col-md-8">
                                    <textarea name="description" style="height: 70px" class="form-control" rows="5" type="text" placeholder="Description">{{ $productUnit->description }}</textarea>
                                    <div class="text-danger">
                                        {{ $errors->has('description') ? $errors->first('description'):'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Status</label>
                                <div class="col-md-8">
                                    <select name="status" id="" class="form-control">
                                        <option value="1" {{ $productUnit->status == 1 ? 'selected':'' }}>Active </option>
                                        <option value="0" {{ $productUnit->status == 0 ? 'selected':'' }}>Inactive </option>
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
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i>Update Unit
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
