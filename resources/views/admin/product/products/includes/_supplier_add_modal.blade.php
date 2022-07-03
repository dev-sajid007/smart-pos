<div class="modal fade" id="addnew_supplier" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <form class="form-horizontal" action="{{route('suppliers.index')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group row add_asterisk">
                        <label class="control-label col-md-3">Name</label>
                        <div class="col-md-8">
                            <input name="name" value="{{ old('name') }}" class="form-control" type="text" placeholder="Name">
                            <div class="text-danger">
                                {{ $errors->has('name') ? $errors->first('name'):'' }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row add_asterisk">
                        <label class="control-label col-md-3">Company Name</label>
                        <div class="col-md-8">
                            <input name="company_name" value="{{ old('company_name') }}" class="form-control"
                                   type="text" placeholder="Name">
                            <div class="text-danger">
                                {{ $errors->has('company_name') ? $errors->first('company_name'):'' }}
                            </div>
                        </div>
                    </div>


                    <div class="form-group row add_asterisk">
                        <label class="control-label col-md-3">Email</label>
                        <div class="col-md-8">
                            <input name="email" value="{{ old('email') }}" class="form-control" type="email"
                                   placeholder="Email">
                            <div class="text-danger">
                                {{ $errors->has('email') ? $errors->first('email'):'' }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row add_asterisk">
                        <label class="control-label col-md-3">Phone</label>
                        <div class="col-md-8">
                            <input name="phone" value="{{ old('phone') }}" class="form-control" type="text"
                                   placeholder="Phone">
                            <div class="text-danger">
                                {{ $errors->has('phone') ? $errors->first('phone'):'' }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row add_asterisk">
                        <label class="control-label col-md-3">Address</label>
                        <div class="col-md-8">
                            <textarea name="address" class="form-control" style="height: 70px" rows="5" type="text" placeholder="Address">{{ old('address') }}</textarea>
                            <div class="text-danger">
                                {{ $errors->has('address') ? $errors->first('address'):'' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check-circle"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>