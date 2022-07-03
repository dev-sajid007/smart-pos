
<div class="modal fade" id="addnew" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('parties.store') }}">
                    @csrf

                    <div class="form-group row add_asterisk">
                        <label class="control-label col-md-3">Name</label>
                        <div class="col-md-8">
                            <input name="name" value="{{ old('name') }}" class="form-control" type="text" placeholder="Name">
                            <div class="text-danger">{{ $errors->has('name') ? $errors->first('name'):'' }}</div>
                        </div>
                    </div>

                    <div class="form-group row add_asterisk">
                        <label class="control-label col-md-3">Email</label>
                        <div class="col-md-8">
                            <input name="email" value="{{ old('email') }}" class="form-control" type="email" placeholder="Email">
                            <div class="text-danger"> {{ $errors->has('email') ? $errors->first('email'):'' }}</div>
                        </div>
                    </div>

                    <div class="form-group row add_asterisk">
                        <label class="control-label col-md-3">Phone</label>
                        <div class="col-md-8">
                            <input name="phone" value="{{ old('phone') }}" class="form-control" type="number" placeholder="Phone">
                            <div class="text-danger">{{ $errors->has('phone') ? $errors->first('phone'):'' }}</div>
                        </div>
                    </div>

                    <div class="form-group row add_asterisk">
                        <label class="control-label col-md-3">Address</label>
                        <div class="col-md-8">
                            <textarea name="address" class="form-control" rows="5" type="text" placeholder="Address">{{ old('address') }}</textarea>
                            <div class="text-danger">{{ $errors->has('address') ? $errors->first('address'):'' }}</div>
                        </div>
                    </div>

                    <div class="tile-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-primary pull-right" type="submit" type="submit">
                                    <i class="fa fa-fw fa-lg fa-check-circle"></i>Add Party
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
