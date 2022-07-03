@extends('admin.master')
@section('content')

    <main class="app-content">
        
        <div class="row">
            <div class="col-md-12">
                
                @include('partials._alert_message')

                <div class="tile">
                    <a href="{{ route('subcategories.create') }}" class="btn btn-primary" style="float: right;">
                        <i class="fa fa-plus"></i> Add Sub Category
                    </a>

                    <h3 class="tile-title">Sub Category List </h3>

                    <div class="tile-body table-responsive" style="min-height: 740px !important">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Sub Category Name</th>
                                    <th>Category</th>
                                    <th width="10%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($subcategories as $key => $sub_category)
                                    <tr>
                                        <td>{{ $key + $subcategories->firstItem() }}</td>
                                        <td>{{ $sub_category->sub_category_name }}</td>
                                        <td>{{ optional($sub_category->category)->category_name }}</td>
                                        <td class="text-center">

                                            <div class="btn-group btn-corner">
                                                <a class="btn btn-info btn-sm" title="Edit" href="{{ route('subcategories.edit',$sub_category->id) }}"> 
                                                    <i class="fa fa-edit"></i> 
                                                </a> 
                                                <button type="button" class="btn btn-danger btn-sm" title="Delete" onclick="delete_item('{{ route('subcategories.destroy', $sub_category->id) }}')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $subcategories])
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- delete form -->
    @include('partials._delete_form')

@endsection



@section('footer-script')


    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>

    <script>
        function formSubmit(id) {
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this data !",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel plz!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $('#deleteForm_' + id).submit();
                } else {
                    swal("Cancelled", "Your data is safe :)", "error");
                }
            });
        }
    </script>

@endsection