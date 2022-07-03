<div class="modal fade" id="addNew" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <form class="form-horizontal productAddForm" method="POST" >
            @csrf
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
                    <button type="button"  onclick="resetForm()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="margin-top: -10px">
                    <div class="row">
                        <div class="overlay loading">
                            <div class="m-loader mr-4">
                                <svg class="m-circular" viewBox="25 25 50 50">
                                    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"/>
                                </svg>
                            </div>
                            <h3 class="l-text">Loading</h3>
                        </div>

                        <div class="col-12">
                            <ul class="error-ul">
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <input name="supplier_id"  class="form-control new_supplier_id"
                                   type="hidden">
                            <div class="form-group add_asterisk">
                                <label class="control-label">Product Name</label>
                                <textarea name="product_name" class="form-control" style="height: 55px"
                                          placeholder="Product Name"></textarea>
                            </div>
                            <div class="form-group add_asterisk">
                                <label class="control-label">Category</label>
                                <select name="fk_category_id" id="fk_category_id" class="form-control select2">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="form-group add_asterisk">
                                <label class="control-label">Product Unit</label>
                                <select name="fk_product_unit_id" id="fk_product_unit_id" class="form-control select2">
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Product Description</label>
                                <textarea name="product_description" style="height: 150px" class="form-control"
                                          placeholder="Product Description">{{ old('product_description') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group add_asterisk">
                                <label class="control-label">Purchase Price</label>
                                <input name="product_cost" value="{{ old('product_cost') }}" class="form-control"
                                       type="text" placeholder="Product Cost">
                            </div>
                            <div class="form-group  add_asterisk">
                                <label class="control-label">Sales Price</label>
                                <input name="product_price" step="0.01" value="{{ old('product_price') }}"
                                       class="form-control" type="text" placeholder="Product Price">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Alert Quantity</label>
                                <input name="product_alert_quantity"
                                       value="{{ old('product_alert_quantity') ?: 10 }}" class="form-control"
                                       type="text" placeholder="Alert Quantity">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Opening Quantity</label>
                                <input name="opening_quantity"
                                       value="0" class="form-control"
                                       type="text" placeholder="Opening Quantity">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Reference No</label>
                                <input name="product_reference" value="{{ old('product_reference') }}"
                                       class="form-control" type="text" placeholder="Reference">
                            </div>


                            <div class="form-group">
                                <label class="control-label">Tax(%)</label>
                                <input name="tax" value="{{ old('tax') ? : '0' }}" class="form-control" type="text"
                                       placeholder="Tax">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tile-footer">
                    <div class="modal-footer">
                        <button type="button" onclick="resetForm()" class="btn btn-secondary"
                                data-dismiss="modal">Close</button>
                        <button class="btn btn-primary"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>Add Product
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

