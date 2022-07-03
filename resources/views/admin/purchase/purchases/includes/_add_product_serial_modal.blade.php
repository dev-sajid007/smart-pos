
<div class="modal fade" id="add_product_serial_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="add_product_serial_modal_label">Add Product Serial</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>



            <div class="modal-body">
                <div class="row">
                    <div class="px-3 col-12">
                        <label>Serials</label>
                        <textarea class="form-control product-serial-textarea" onkeyup="countProductSerial(this)" style="min-height: 200px"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="width: 100%">
                <div class="row " style="width: 100%; margin: 0">
                    <div class="col-sm-6">
                        <b class="text-success">Total serial: <span class="total-serial-count">0</span></b>

                        <input type="hidden" class="product-id-holder">
                    </div>
                    <div class="col-sm-6 text-right">
                        <div class="btn-group btn-corner">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button class="btn btn-primary pull-right" onclick="addFinalizeProductSerial()" type="button">
                                <i class="fa fa-fw fa-lg fa-check-circle"></i>Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
