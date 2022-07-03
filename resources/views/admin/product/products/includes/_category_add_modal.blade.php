
<div class="modal fade" id="addnew_category" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <form class="form-horizontal" method="post" action="{{ route('categories.store') }}">
                @csrf

                <div class="modal-body">
                    <div class="form-group row add_asterisk">
                        <div class="input-group mb-3 mx-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="height: 33px !important;">Name</span>
                            </div>
                            <input name="category_name" class="form-control" type="text" placeholder="Category Name">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary pull-right" type="submit">
                        <i class="fa fa-fw fa-lg fa-check-circle"></i>Add Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
