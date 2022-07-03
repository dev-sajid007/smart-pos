@extends('admin.master')
@section('title', 'Customer Create')

@section('content')
    <main class="app-content">
        <div class="modal fade" id="addnew" data-backdrop="static" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New @yield('title')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form class="form-horizontal" action="{{ route('customer-category.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">

                            <div class="form-group row add_asterisk">
                                <label for="name" class="control-label col-md-4">Category Name </label>
                                <div class="col-md-8">
                                    <input type="text" id="name" name="name" value="{{ old('account_name') }}"
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="Customer Category Name">

                                    @error('account_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="control-label col-md-4">Description</label>
                                <div class="col-md-8">
                                    <textarea id="description" name="description" value="{{ old('description') }}"
                                              type="text" cols="30" rows="10"
                                              class="form-control @error('description') is-invalid @enderror"
                                              placeholder="Description"></textarea>

                                    @error('account_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="form-group row add_asterisk">
                                <label for="status" class="control-label col-md-4">Status </label>
                                <div class="col-md-8">
                                    <div class="animated-radio-button" id="status">
                                        <label>
                                            <input type="radio" name="status" value="1" checked><span
                                                    class="label-text">Published</span>
                                        </label>
                                        &nbsp
                                        &nbsp;
                                        <label>
                                            <input type="radio" name="status" value="0"><span class="label-text">Unpublished</span>
                                        </label>
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

        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <div class="tile">
                    <a href="{{route('customers.index')}}" class="btn btn-primary pull-right">
                        <i class="fa fa-eye"></i> View Customer
                    </a>
                    <h3 class="tile-title">Add New Customer</h3>

                    <div class="tile-body">
                        <hr>
                        <form class="form-horizontal pl-5" method="POST" enctype="multipart/form-data" action="{{ route('customers.store') }}">
                            @csrf


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
                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-2">Phone</label>
                                <div class="col-md-6">
                                    <input name="phone" value="{{ old('phone') }}" class="form-control" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Phone" required>
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
                                        @foreach($customer_categories as $customer_category)
                                            <option value="{{ $customer_category->id }}">{{ $customer_category->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">
                                        {{ $errors->has('address') ? $errors->first('address'):'' }}
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-info" type="button" data-toggle="modal" data-target="#addnew" title="Add New Customer Category">
                                        <i class="fa fa-plus"></i>
                                    </button>
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

                            <!-- Image -->
                            <div class="form-group row">
                                <label class="control-label col-md-2">Image</label>
                                <div class="col-md-6">
                                    <input name="image" class="form-control" type="file" value="">
                                    <div class="text-danger">Image size 300 x 250 px</div>
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


                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-9">
                                        <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-fw
                                        fa-lg fa-check-circle"></i>Add Customer
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


    <script type="text/javascript">
        $('.select2').select2();
    </script>
@endsection

@section('js')

    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.js"></script>

    <!-- Page specific javascripts-->



    <script type="text/javascript">

        @if(Session::get('massage'))
        swal({
            title: "Success!",
            text: "{{ Session::get('massage') }}",
            type: "success",
            timer: 3000
        });
        @endif
    </script>

@stop
