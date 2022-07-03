@extends('admin.master')

@section('title', ' - Product Package')

@push('style')
@endpush

@section('content')


    <main class="app-content">
        <form class="form-horizontal" method="POST" action="{{ route('product-packages.update', $productPackage->id) }}">
            @csrf @method('PUT')

            <div class="row">
                <div class="col-sm-10 mx-auto">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <i class="fa fa-edit"></i> Edit Product Package
                            <a href="{{ route('product-packages.index') }}" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-eye"></i> Package List
                            </a>
                        </div>

                        <div class="card-body">


                            @include('partials._alert_message')


                            <!-- product entry section -->
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="40%">Product Name</th>
                                                <th width="20%">Quantity</th>
                                                <th width="20%">Unit Cost</th>
                                                <th width="10%"></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select class="form-control select-product select2 mr-0" style="width: 100%">
                                                        <option value="">Select</option>
                                                        @foreach($products as $key => $product)
                                                            <option value="{{ $product->id }}"
                                                                    data-product-name="{{ $product->product_name }}"
                                                                    data-product-code="{{ $product->product_code }}"
                                                                    data-product-price="{{ $product->product_price }}"
                                                                >
                                                                {{ $product->product_name }} ({{ $product->product_code }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="select-quantity form-control" onkeypress="event.charCode >= 46 && event.charCode <= 57" type="text" value="">
                                                </td>
                                                <td>
                                                    <input class="select-unit-price add-new-product-row form-control" onkeypress="event.charCode >= 46 && event.charCode <= 57" type="text" value="">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary" onclick="insertNewItem()"><i class="fa fa-plus"></i> Add</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                            <!-- product display section -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="3%">Sl.</th>
                                                <th>Product Name</th>
                                                <th>Product Code</th>
                                                <th width="100px">Product Qty</th>
                                                <th width="100px">Unit Cost</th>
                                                <th width="100px">Subtotal</th>
                                                <th width="5%"></th>
                                            </tr>
                                        </thead>

                                        <tbody class="product-display">
                                            @foreach ($productPackage->package_details as $key => $detail)
                                                <tr>
                                                    <td class="item-serial">{{ $key + 1 }}</td>
                                                    <td>{{ $detail->product->product_name }}
                                                        <input type="hidden" name="product_ids[]" class="product-id" value="{{ $detail->product_id }}">
                                                    </td>
                                                    <td>
                                                        {{ $detail->product->product_code }}
                                                        <input type="hidden" name="detail_ids[]" value="{{ $detail->id }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" readonly name="quantities[]" class="product-quantity form-control text-center" value="{{ $detail->quantity }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" readonly name="unit_cost[]" class="product-price form-control text-center" value="{{ $detail->price }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" readonly name="product_sub_total[]" class="product-row-subtotal form-control text-center" value="{{ $detail->amount }}">
                                                    </td>
                                                    <td class="text-right">
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <th colspan="5"><strong>Total: </strong></th>
                                                <th class="grand-total text-center">{{ $productPackage->package_details->sum('amount') }}</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Package Name</span>
                                        </div>
                                        <input class="form-control" style="height: 36px" name="name" value="{{ old('name', $productPackage->name) }}" >
                                    </div>
                                </div>

                                <div class="col-sm-2 text-right">
                                    <button class="btn btn-info text-light" type="submit"><i class="fa fa-edit"></i> Update Package</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </form>
    </main>

@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('assets/custom_js/product-package.js') }}"></script>
@endsection
