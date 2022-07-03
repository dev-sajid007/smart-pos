@extends('admin.master')
@section('content')

    <main class="app-content">
        
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">Add New Category</h3>
                    <div class="tile-body" style="min-height: 740px !important">
                        <form class="form-horizontal" method="post" action="{{ route('subcategories.update',$sub_category->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Category</label>
                                <div class="col-md-8">
                                    <select name="fk_category_id" class="form-control select2">
                                        @foreach($category as $value)
                                            <option value="{{ $value->id }}" {{ $sub_category->fk_category_id == $value->id ? 'selected' : '' }}>{{ $value->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Category Name</label>
                                <div class="col-md-8">
                                    <input name="sub_category_name" class="form-control" type="text" value="{{ $sub_category->sub_category_name }}" placeholder="Category Name">
                                </div>
                            </div>


                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i>Update Sub Category
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
    <script src="{{ asset('assets/admin/js/plugins/select2.min.js') }}"></script>
@endsection