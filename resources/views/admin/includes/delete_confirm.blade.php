<div id="DeleteModal" class="modal fade text-danger" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <form action="" id="deleteForm" method="post">
            @csrf
            @method("DELETE")

            <div class="modal-content">

                <div class="modal-body">
                    <h1 class="text-center"><i class=" fa fa-question-circle"></i></h1>
                    <h4 class="text-center">Are You Sure Want To Delete ?</h4>
                </div>
                <div class="modal-footer">
                    <center>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="" class="btn btn-danger" data-dismiss="modal"
                                onclick="formSubmit()">Yes, Delete
                        </button>
                    </center>
                </div>
            </div>
        </form>
    </div>
</div>


