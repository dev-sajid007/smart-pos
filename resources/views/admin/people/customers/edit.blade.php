
@extends('admin.master')
@section('title', 'Customer Edit')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/custom_css/image_full_screen.css') }}">
@endsection

@section('content')

    <main class="app-content">
        <!-- The Modal -->
        @include('admin.includes.image_full_screen')

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



                    <!-- Category Create Modal -->
                    <form class="form-horizontal" action="{{ route('customer-category.store') }}" enctype="multipart/form-data" method="POST">
                        @csrf

                        <div class="modal-body">

                            <!-- Category Name -->
                            <div class="form-group row add_asterisk">
                                <label for="name" class="control-label col-md-4">Category Name </label>
                                <div class="col-md-8">
                                    <input type="text" id="name" name="name" value="{{ old('account_name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Customer Category Name">

                                    @error('account_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Descrition -->
                            <div class="form-group row">
                                <label for="description" class="control-label col-md-4">Description</label>
                                <div class="col-md-8">
                                    <textarea id="description" name="description" value="{{ old('description') }}" type="text" class="form-control @error('description') is-invalid @enderror" placeholder="Description"></textarea>

                                    @error('account_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="form-group row add_asterisk">
                                <label for="status" class="control-label col-md-4">Status </label>
                                <div class="col-md-8">
                                    <div class="animated-radio-button" id="status">
                                        <label>
                                            <input type="radio" name="status" value="1" checked><span class="label-text">Published</span>
                                        </label>
                                        &nbsp;
                                        <label>
                                            <input type="radio" name="status" value="0"><span class="label-text">Unpublished</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Action -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"> <i class="fa fa-check-circle"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <div class="tile">
                    <a href="{{ route('customers.index') }}" class="btn btn-primary pull-right" style="float: right;"><i class="fa fa-eye"></i> View Customers</a>

                    <h3 class="tile-title">Edit Customer</h3> <hr>

                    <div class="tile-body">
                        <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="{{ route('customers.update', $customer->id) }}">
                            @csrf @method("PUT")

                            <!-- Name -->
                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-2">Name</label>
                                <div class="col-md-6">
                                    <input name="name" value="{{ $customer->name }}" class="form-control" type="text"
                                           placeholder="Name">
                                    <div class="text-danger">
                                        {{ $errors->has('name') ? $errors->first('name') : '' }}
                                    </div>
                                </div>
                            </div>


                            <!-- Email -->
                            <div class="form-group row">
                                <label class="control-label col-md-2">Email</label>
                                <div class="col-md-6">
                                    <input name="email" value="{{ $customer->email }}" class="form-control" type="email" placeholder="Email">
                                    <div class="text-danger">
                                        {{ $errors->has('email') ? $errors->first('email') : '' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Phone/Mobile -->
                            <div class="form-group row">
                                <label class="control-label col-md-2">Phone</label>
                                <div class="col-md-6">
                                    <input name="phone" value="{{ $customer->phone }}" class="form-control" type="number" placeholder="Phone">
                                    <div class="text-danger">
                                        {{ $errors->has('phone') ? $errors->first('phone'):'' }}
                                    </div>
                                </div>
                            </div>


                            <!-- Customer Company -->
                            <div class="form-group row">
                                <label class="control-label col-md-2">Company</label>
                                <div class="col-md-6">
                                    <input name="company_name" class="form-control" value="{{ old('company_name', $customer->company_name) }}" placeholder="Customer Company Name">
                                    <div class="text-danger">
                                        {{ $errors->has('company_name') ? $errors->first('company_name'):'' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="form-group row">
                                <label class="control-label col-md-2">Address</label>
                                <div class="col-md-6">
                                    <textarea name="address" class="form-control h-100" type="text" placeholder="Address">{{ $customer->address }}</textarea>
                                    <div class="text-danger">
                                        {{ $errors->has('address') ? $errors->first('address'):'' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Customer category -->
                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-2">Customer Category</label>
                                <div class="col-md-6">
                                    <select name="customer_category_id" class="form-control" id="customer_categories">
                                        @foreach($customer_categories as $customer_category)
                                            <option {{ $customer_category->id == $customer->customer_category_id ? 'selected' : '' }}
                                                    value="{{ $customer_category->id }}">
                                                {{ $customer_category->name }}
                                            </option>
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

                            <!-- Customer previous due -->
                            <div class="form-group row">
                                <label class="control-label col-md-2">Customer Previous Due</label>
                                <div class="col-md-6">
                                    <input value="{{ $customer->customer_previous_due }}" name="customer_previous_due" class="form-control" type="number" placeholder="Previous Due">
                                </div>
                            </div>

                            <!-- Due Limit -->
                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-2">Due Limit</label>
                                <div class="col-md-6">
                                    <input name="due_limit" value="{{ old('due_limit', $customer->due_limit) }}" class="form-control" type="text" onkeypress="return event.charCode >= 48 && event.charCode <=57" placeholder="Due Limit">
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

                            <!-- Set customer as default -->
                            <div class="form-group row">
                                <label class="control-label col-md-2">Set Default</label>
                                <div class="col-md-6">
                                    <div class="animated-checkbox">
                                        <label>
                                            <input name="default" value="1" {{ $customer->default ? 'checked' : '' }} type="checkbox"><span class="label-text"><b>Default</b></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Image view -->
                            @if ($customer->image)
                                <div class="form-group row">
                                    <div class="col-md-6 offset-2">
                                        <img width="100" class="full-screen-image" style="cursor: pointer" title="Click on image to fullscreen" height="100" src="{{ asset('uploads/customers/' . $customer->image) }}">
                                    </div>
                                </div>
                            @endif


                            <!-- Action -->
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <button class="btn btn-primary pull-right" type="submit" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Update Customer</button>
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

    <script type="text/javascript">

        var select_level = document.getElementById('customer_level');
        select_level.options[select_level.options.selectedIndex].selected = true;

    </script>

@endsection

@section('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.js"></script>

    <!-- Page specific javascript -->
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

    <script type="text/javascript" src="{{ asset('assets/custom_js/image_full_screen.js') }}"></script>
@stop
