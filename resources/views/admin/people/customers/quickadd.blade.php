<div class="modal fade" id="addnew" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <form class="form-horizontal customerAddForm" method="POST" action="{{ route('customers.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Customer</h5>
                    <button type="button" class="close" onclick="resetCustomerForm()" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                    </div>
                    <!-- Name -->
                    <div class="form-group row add_asterisk">
                        <label class="control-label col-md-2">Name</label>
                        <div class="col-md-6">
                            <input name="name" value="{{ old('name') }}" class="form-control" type="text" placeholder="Name">
                            <div class="text-danger">
                                {{ $errors->has('name') ? $errors->first('name'):'' }}
                            </div>
                        </div>
                    </div>
                    <!-- Email -->
                    <div class="form-group row">
                        <label class="control-label col-md-2">Email</label>
                        <div class="col-md-6">
                            <input name="email" value="{{ old('email') }}" class="form-control" type="email" placeholder="Email">
                            <div class="text-danger">
                                {{ $errors->has('email') ? $errors->first('email'):'' }}
                            </div>
                        </div>
                    </div>
                    <!-- Phone/Mobile -->
                    <div class="form-group row">
                        <label class="control-label col-md-2">Phone</label>
                        <div class="col-md-6">
                            <input name="phone" value="{{ old('phone') }}" class="form-control" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Phone">
                            <div class="text-danger">
                                {{ $errors->has('phone') ? $errors->first('phone'):'' }}
                            </div>
                        </div>
                    </div>
                    <!-- Customer Company -->
                    <div class="form-group row">
                        <label class="control-label col-md-2">Company</label>
                        <div class="col-md-6">
                            <input name="company_name" class="form-control" value="{{ old('company_name') }}" placeholder="Customer Company Name">
                            <div class="text-danger">
                                {{ $errors->has('company_name') ? $errors->first('company_name'):'' }}
                            </div>
                        </div>
                    </div>
                    <!-- Address -->
                    <div class="form-group row ">
                        <label class="control-label col-md-2">Address</label>
                        <div class="col-md-6">
                            <textarea name="address" style="height: 70px" class="form-control" rows="5" type="text" placeholder="Address">{{ old('address') }}</textarea>
                            <div class="text-danger">
                                {{ $errors->has('address') ? $errors->first('address'):'' }}
                            </div>
                        </div>
                    </div>
                    <!-- Customer Category -->
                    <div class="form-group row add_asterisk">
                        <label class="control-label col-md-2">Customer Category</label>
                        <div class="col-md-6">
                            <select name="customer_category_id" class="form-control" id="customer_category">
                                @foreach($categories as $customer_category)
                                    <option value="{{ $customer_category->id }}">{{ $customer_category->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger">
                                {{ $errors->has('address') ? $errors->first('address'):'' }}
                            </div>
                        </div>
                    </div>
                    <!-- Opening Balance -->
                    <div class="form-group row add_asterisk">
                        <label class="control-label col-md-2">Opening Balance</label>
                        <div class="col-md-6">
                            <input name="customer_previous_due" value="{{ old('customer_previous_due') ?: '0' }}" class="form-control" type="number" placeholder="Previous Due">
                            <div class="text-danger">
                                {{ $errors->has('customer_previous_due') ? $errors->first('customer_previous_due'):'' }}
                            </div>
                        </div>
                    </div>
                    <!-- Due Limit -->
                    <div class="form-group row add_asterisk">
                        <label class="control-label col-md-2">Due Limit</label>
                        <div class="col-md-6">
                            <input name="due_limit" value="{{ old('due_limit') ?? 999999999 }}" class="form-control" type="text" onkeypress="return event.charCode >= 48 && event.charCode <=57" placeholder="Due Limit">
                            <div class="text-danger">
                                {{ $errors->has('due_limit') ? $errors->first('due_limit') : '' }}
                            </div>
                        </div>
                    </div>
                    <!-- Set customer as a default -->
                    <div class="form-group row add_asterisk">
                        <label class="control-label col-md-2">Set Default</label>
                        <div class="col-md-6">
                            <div class="animated-checkbox">
                                <label>
                                    <input name="default" value="1" type="checkbox"><span class="label-text"><b>Default</b></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tile-footer">
                    <div class="modal-footer">
                        <button type="button" onclick="resetCustomerForm()" class="btn btn-secondary"
                                data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>Add Customer
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

