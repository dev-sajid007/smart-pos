@extends('admin.master')
@section('content')

    <main class="app-content">
        
        <div class="row">
            <div class="col-md-12">
                
                
                @include('partials._alert_message')

                <div class="tile">
                    <a href="{{ route('subcategories.index') }}" class="btn btn-primary pull-right" style="float: right;">
                        <i class="fa fa-eye"></i>View Sub Category
                    </a>
                    <h3 class="tile-title">Add New Sub Category</h3>

                    <div class="tile-body" style="min-height: 740px !important">
                        <form class="form-horizontal" method="post" action="{{ route('subcategories.store') }}">
                            @csrf
                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Parent Category</label>
                                <div class="col-md-8">
                                    <select name="fk_category_id" class="form-control select2">

                                        <option value="">--Select Category--</option>
                                        
                                        @foreach($categories as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Sub Category Name</label>
                                <div class="col-md-8">
                                    <input name="sub_category_name" class="form-control" type="text" placeholder="Category Name">
                                </div>
                            </div>


                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right" type="submit">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i>Add Sub Category
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
@endsection