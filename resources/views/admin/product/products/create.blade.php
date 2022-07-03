


@extends('admin.master')
@section('title', 'Product Create')


@section('content')
    <main class="app-content">
        @include('admin.product.products.includes._supplier_add_modal')
        @include('admin.product.products.includes._category_add_modal')


        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')


                <div class="tile">
                    <a href="{{  route('products.index') }}" class="btn btn-success pull-right " style="float: right;">
                        <i class="fa fa-eye"></i> Product List
                    </a>
                    <h3 class="tile-title">Add New Product</h3>
                    <hr>

                    <div class="tile-body">
                        <form class="form-horizontal" id="productCreateForm" method="POST" enctype="multipart/form-data" action="{{ route('products.store') }}">
                            @csrf

                            <div class="row">
                                <!-- LEFT SIDE -->
                                <div class="col-md-6">
                                    @include('admin.product.products.includes.left-side-input-fields')

                                    {{-- @if ($warehouses->count() > 0 && ($settings->where('title', 'Warehouse Wise Product Stock')->where('options', 'yes')->count() > 0))
                                    @include('admin.product.products.includes.warehouse-product-create')
                                    @endif --}}
                                </div>

                                <!-- RIGHT SIDE -->
                                <div class="col-md-6">
                                    @include('admin.product.products.includes.right-side-input-fields')

                                    @if ($warehouses->count() > 0)
                                        <button class="btn btn-primary pull-right mr-5 save-btn" type="button">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i> Save Product
                                        </button>
                                    @endif
                                </div>

                                <!-- ACTION -->
                                @if ($warehouses->count() < 1)
                                    <div class="col-md-12">
                                        <button class="btn btn-primary pull-right mr-5 save-btn" type="button">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i> Save Product
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <input type="hidden" class="subcategory-url" value="{{ route('subcategories.index') }}">
@endsection

@section('footer-script')
    <script type="text/javascript" src="{{ asset('assets/custom_js/product-create.js') }}"></script>
@stop
