@extends('admin.master')
@section('title', ' - Serial Product List')

@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">

                <div class="tile">
                    @if (hasPermission('products.create'))
                        <a href="{{ route('products.create') }}" class="btn btn-primary" style="float: right;">
                            <i class="fa fa-plus"></i> Add Product
                        </a>
                    @endif

                    <h3 class="tile-title"><i class="fa fa-list"></i> Serial Product List </h3>

                    <!-- Product Search Filter -->

                    <div class="row">
                        <div class="col-md-12">
                            <form action="" method="get">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="10%"></td>
                                        <td class="no-print" width="30%">
                                            <label class="control-label">Select Product </label>
                                            <select name="product_id" class="form-control supplier_id select2">
                                                <option value="">Select</option>
                                                @foreach ($productList as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ $id == request('product_id') ? 'selected' : '' }}>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td width="30%" class="no-print">
                                            <label class="control-label">Search by Serial</label>
                                            <input type="text" class="form-control" name="search"
                                                value="{{ request('search') }}" autocomplete="off">
                                        </td>

                                        <td width="30%" class="no-print">
                                            <div class="form-group" style="margin-top: 26px;">
                                                <button class="btn btn-primary"><i class="fa fa-check"></i> Search </button>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>

                    <div class="tile-body table-responsive">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th>Purchase Invoice</th>
                                    {{-- <th style="width: 89px !important;">Purchase Invoice</th> --}}
                                    <th>Sale Invoice</th>
                                    <th>Sale Date</th>
                                    <th>Serial</th>
                                    <th>Product Name</th>
                                    <th>Sold</th>
                                    <th>Source</th>
                                    <th class="text-right">Price</th>

                                    {{-- <th width="10%" class="text-center">Action</th> --}}
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($SerialList as $key => $serial)
                                    <tr>
                                        <td>{{ $key + $SerialList->firstItem() }}</td>
                                        <td>
                                            @if ($serial->source ==  'Purchase')
                                                <a href="{{ route('purchases.show', $serial->purchase_id) }}">{{ '#P-' . str_pad($serial->purchase_id, 5, "0", STR_PAD_LEFT) }}</a>
                                            @endif
                                            
                                        <td>
                                            @if ($serial->is_sold ==  1)
                                                @if ($serial->sale_id)
                                                    <a href="{{ route('sales.show', $serial->sale_id) }}">{{ '#S-'.$serial->sales->sale_invoice_id }}</a>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if ($serial->is_sold ==  1)
                                                @if ($serial->sale_id)
                                                    {{ \Carbon\Carbon::parse($serial->sales->sale_date)->format('Y-m-d') }}
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $serial->serial }}</td>
                                        <td>{{ $serial->product->product_name }}</td>
                                        <td >{{ ($serial->is_sold==1) ? 'Yes' : 'No' }}</td>
                                        <td>{{ $serial->source }}</td>
                                        <td class="text-right">{{ $serial->product->product_price }}</td>
                                       
                                        {{-- <td class="text-center">
                                            @include('admin.product.products.includes.actions')
                                        </td> --}}
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $SerialList])
                    </div>
                </div>
            </div>
        </div>
    </main>

<script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>

@endsection
