

<div class="modal fade" id="create-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create New @yield('title')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <form class="form-horizontal" action="{{ route('customer-category.store') }}" method="POST">
                @csrf

                <input type="hidden" class="modal-type" name="modal-type" value="create">

                <div class="modal-body">
                    <!-- Customer Category Name -->
                    <div class="input-group mb-1">
                        <div class="input-group-prepend" style="height: 30px !important;">
                            <span class="font-weight-bold input-group-text" style="width: 120px !important;">Category Name</span>
                        </div>
                        <input type="text" class="form-control" style="height: 30px !important;" name="name" value="{{ old('name') }}">

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Customer Category Name -->
                    <div class="input-group mb-1">
                        <div class="input-group-prepend" style="min-height: 80px !important;">
                            <span class="font-weight-bold input-group-text" style="width: 120px !important;">Description</span>
                        </div>
                        <textarea type="text" class="form-control" style="min-height: 80px !important;" name="description" >{{ old('description') }}</textarea>

                        @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Amount of -->
                    <div class="input-group mb-1">
                        <div class="input-group-prepend" style="height: 30px !important;">
                            <span class="font-weight-bold input-group-text" style="width: 120px !important;">Amount Of</span>
                        </div>
                        <input type="text" class="form-control" onkeypress="return event.charCode >= 46 && event.charCode <= 57" style="height: 30px !important;" name="amount_of" value="{{ old('amount_of') }}">
                    </div>

                    <!-- Amount -->
                    <div class="input-group mb-1">
                        <div class="input-group-prepend" style="height: 30px !important;">
                            <span class="font-weight-bold input-group-text" style="width: 120px !important;">Amount</span>
                        </div>
                        <input type="text" class="form-control" onkeypress="return event.charCode >= 46 && event.charCode <= 57" style="height: 30px !important;" name="amount" value="{{ old('amount') }}">
                    </div>


                    <!-- Amount Type -->
                    <div class="input-group mb-1">
                        <label for="type" class="control-label col-md-4">Amount Type </label>
                        <div class="col-md-8">
                            <div class="animated-radio-button" id="type">
                                <label>
                                    <input type="radio" name="type" value="amount" checked><span class="label-text">Amount</span>
                                </label>
                                &nbsp;
                                <label>
                                    <input type="radio" name="type" value="percent"><span class="label-text">Percent</span>
                                </label>
                            </div>
                        </div>
                    </div>


                    <!-- Status -->
                    <div class="input-group mb-1">
                        <label for="status" class="control-label col-md-4">Status </label>
                        <div class="col-md-8">
                            <div class="animated-radio-button" id="status">
                                <label>
                                    <input type="radio" name="status" value="1" checked><span class="label-text">Active</span>
                                </label>
                                &nbsp;
                                <label>
                                    <input type="radio" name="status" value="0"><span class="label-text">In Active</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action -->
                <div class="modal-footer">
                    <div class="btn-group btn-corner">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fa fa-times"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check-circle"></i> Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
