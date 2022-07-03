@extends('admin.master')
@section('title', ' - Product List')

@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">

                <div class="tile">
                    @if(hasPermission('products.create'))
                        <div class="btn-group btn-corner pull-right">
                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Add Product
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-success">
                                <i class="fa fa-list"></i> Product List
                            </a>
                        </div>
                    @endif
                    <h3 class="tile-title"><i class="fa fa-list"></i> Product List </h3>

                                        
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" method="get">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="10%"></td>
                                        <td width="20%">
                                            <label class="control-label">Select Product </label>
                                            <select name="product_id" class="form-control supplier_id select2">
                                                <option value="">Select</option>
                                                @foreach ($productList as $id => $name)
                                                    <option value="{{ $id }}" {{ $id == request('product_id') ? 'selected':'' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td class="no-print" width="20%">
                                            <label class="control-label">Select Brand </label>
                                            <select name="brand_id" class="form-control supplier_id select2">
                                                <option value="">Select</option>
                                                @foreach ($brands as $id => $name)
                                                    <option value="{{ $id }}" {{ $id == request('brand_id') ? 'selected':'' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="no-print" width="20%">
                                            <label class="control-label">Select Category </label>
                                            <select name="fk_category_id" class="form-control supplier_id select2">
                                                <option value="">Select</option>
                                                @foreach ($categories as $id => $name)
                                                    <option value="{{ $id }}" {{ $id == request('fk_category_id') ? 'selected':'' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td width="20%" class="no-print">
                                            <label class="control-label">Search by anything</label>
                                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" autocomplete="off">
                                        </td>

                                        <td width="20%" class="no-print">
                                            <div class="form-group" style="margin-top: 26px;">
                                                <button class="btn btn-primary"><i class="fa fa-check"></i> Search </button>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>


                        
                        <form action="{{ route('product-price.update') }}" method="POST" style="width: 100%">
                            @csrf

                            <div class="col-sm-11 mx-auto">
                                <div class="tile-body table-responsive">
                                    <table class="table table-hover table-bordered table-sm" id="sampleTable">
                                        <thead>
                                            <tr>
                                                <th>Sl.</th>
                                                <th>Name</th>
                                                <th>Product Code</th>
                                                <th>Unit</th>
                                                <th class="text-center" width="120px">Purchase Price</th>
                                                <th class="text-center" width="120px">Sale Price</th>
                                            </tr>
                                        </thead>
            
                                        <tbody>
                                            @foreach ($products as $key => $product)
                                                <tr>
                                                    <td>
                                                        {{ $key + $products->firstItem() }}
                                                        <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                                    </td>
                                                    <td>{{ $product->product_name }}</td>
                                                    <td>{{ $product->product_code }}</td>
                                                    <td>{{ $product->product_unit->name }}</td>
                                                    <td class="text-center">
                                                        <input type="text" class="form-control text-center" onkeypress="return event.charCode >= 46 && event.charCode <= 57" name="product_costs[]" value="{{ $product->product_cost }}">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="text" class="form-control text-center" onkeypress="return event.charCode >= 46 && event.charCode <= 57" name="product_prices[]" value="{{ $product->product_price }}">
                                                    </td>
                                                </tr>
                                            @endforeach
            
                                        </tbody>
    
                                        <tfoot>
                                            <tr>
                                                <th colspan="40" class="text-right">
                                                    <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> Update Price</button>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
            
                                    @include('admin.includes.pagination', ['data' => $products])
                                </div>
                            </div>
                        </form>
                    </div>


                    
                </div>
            </div>
        </div>
    </main>

@endsection


@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();
    })
</script>
@endsection