@extends('admin.master')

@section('title', ' - Purchase')

@push('style')
    <style>
        .form-group {margin-bottom: 8px;}
        .item-box {padding: 0px 4px;height: 35px;}
        .add-new-product-button{padding: 4.5px;border-radius: 0px 5px 5px 0px;visibility: hidden;}
        .loading{display: none;justify-content: center;align-items: center;width: 100%;position: absolute;height: 100%;z-index: 11111;background: #ffffffc7;}
    </style>
@endpush

@section('content')

    @include('admin.purchases.quickadd', ['errors' => $errors])

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-10 mx-auto">
                        <div class="card">

                            <!-- Heading -->
                            <div class="card-header bg-primary text-white">
                                <i class="fa fa-plus"></i> Add New Customer Price
                                <a href="{{ route('purchases.index') }}" class=" btn-sm pull-right text-white">
                                    <i class="fa fa-list"></i> Customer Pricing List List
                                </a>
                            </div>

                            <div class="card-body">

                                <!-- Filter -->
                                <form class="form-horizontal" method="GET" action="{{ route('customer-product-pricing.create') }}">
                                    <div class="row mb-2">
                                        <div class="col-md-6 pr-0">
                                            <label>Customer</label>
                                            <select name="customer_id" class="form-control mr-0 select2">
                                                <option value="">-Select Customer-</option>
                                                @foreach($customers as $id => $name)
                                                    <option value="{{ $id }}" {{ $id == request('customer_id') ? 'selected' : '' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-2">
                                            <button type="submit" class="btn btn-primary mt-4"><i class="fa fa-search"></i> Load Product</button>
                                        </div>
                                        <br>
                                    </div>
                                </form>



                                <!-- Data store form -->
                                @if (request('customer_id') != '')
                                    <form class="form-horizontal" method="POST" action="{{ route('customer-product-pricing.store') }}">
                                        @csrf
                                        <input type="hidden" name="customer_id" value="{{ request('customer_id') ?? old('customer_id') }}">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-bordered table-sm ">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl.</th>
                                                            <th>Product Code</th>
                                                            <th>Product Name</th>
                                                            <th width="15%" class="text-right">Product Price</th>
                                                            <th width="12%" class="text-right">Customer Price</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="supplier_product">
                                                        @foreach($products as $key => $product)
                                                            <tr>
                                                                <td>{{ $key + $products->firstItem() }}</td>
                                                                <td>
                                                                    <input type="hidden" tabindex="-1" readonly
                                                                           name="product_ids[]" id="product_id_1"
                                                                           class="form-control item-box" autofocus value="{{ $product->id }}">
                                                                    {{ $product->code }}
                                                                </td>
                                                                <td>{{ $product->name }}</td>
                                                                <td class="text-right sale-price">{{ $product->price }}</td>
                                                                <td class="text-right">
                                                                    <input type="text"name="customer_prices[]" value="{{ old('customer_prices')[$key] ?? $product->customer_price }}" class="form-control text-right customer-price" onkeypress="return event.charCode >= 46 && event.charCode <= 57">
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>


                                        <!-- Action -->
                                        <div class="row">
                                            <div class="col-sm-6">{{ $products->appends($_GET)->links() }}</div>
                                            <div class="col-sm-6">
                                                <div class="animated-checkbox float-left">
                                                    <label>
                                                        <input type="checkbox" name="checkAll[]" id="check-all" class="form-control check-all">
                                                        <span class="label-text">All Sale Price Apply to Customer Price</span>
                                                    </label>
                                                </div>
                                                <div class="float-right">
                                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    @include('admin.includes.date_field')
@endsection

@section('footer-script')
    <script type="text/javascript" src="{{ asset('jq/select2Loads.js') }}"></script>

    <script>

        $('#check-all').click(function () {
            if ($(this).is(':checked') == true) {
                $('.sale-price').each(function () {
                    $(this).closest('tr').find('.customer-price').val($(this).text())
                })
            } else {
                $('.customer-price').val('')
            }
        })
    </script>
@stop
