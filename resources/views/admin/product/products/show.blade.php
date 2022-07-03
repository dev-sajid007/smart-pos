@extends('admin.master')

@section('title', 'Product Details')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Show Product</h1>
                <p>Show Product</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="tile table-responsive">
                    <a class="float-right btn btn-sm btn-info" href="{{ route('products.index') }}">Back</a>
                    <h3 class="tile-title">Product Details</h3>

                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Product Code</th>
                                <td>{{ $product->product_code }}</td>
                            </tr>

                            <tr>
                                <th>Product Name</th>
                                <td>{{ $product->product_name }}</td>
                            </tr>

                            <tr>
                                <th>Product Barcode</th>
                                <td>{{ optional($product->barcode)->barcode_number }}</td>
                            </tr>

                            <tr>
                                <th>Product Brand</th>
                                <td>{{ optional($product->brand)->category_name }}</td>
                            </tr>

                            <tr>
                                <th>Product Category</th>
                                <td>{{ optional($product->Category)->category_name }}</td>
                            </tr>

                            @if ($settings->where('title', 'Product Generic Name')->where('options', 'yes')->count() > 0)
                                <tr>
                                    <th>Product Generic Name</th>
                                    <td>{{ optional($product->generic)->name }}</td>
                                </tr>
                            @endif

                            @if ($settings->where('title', 'Product Rak In Product')->where('options', 'yes')->count() > 0)
                            <tr>
                                <th>Product Rak No</th>
                                <td>{{ optional(optional($product->product_rak)->rak)->name }}</td>
                            </tr>
                            @endif

                            <tr>
                                <th>Product Unit</th>
                                <td>{{ optional($product->product_unit)->name }}</td>
                            </tr>

                            @if ($settings->where('title', 'Product Expire Date')->where('options', 'yes')->count() > 0)
                                <tr>
                                    <th>Expire Date</th>
                                    <td>{{ $product->expire_date }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Product Size</th>
                                <td>{{ $product->product_size }}</td>
                            </tr>

                            <tr>
                                <th>Product Cost</th>
                                <td>{{ $product->product_cost }}</td>
                            </tr>

                            <tr>
                                <th>Seals Price</th>
                                <td>{{ $product->product_price }}</td>
                            </tr>

                            <tr>
                                <th>Product Alert Quantity</th>
                                <td>{{ $product->product_alert_quantity }}</td>
                            </tr>

                            <tr>
                                <th>Product Reference</th>
                                <td>{{ $product->product_reference }}</td>
                            </tr>

                            <tr>
                                <th>Supplier Name</th>
                                <td>{{ optional($product->supplier)->name }}</td>
                            </tr>

                            @if ($settings->where('title', 'Product Tax')->where('options', 'yes')->count() > 0)
                                <tr>
                                    <th>Tax</th>
                                    <td>{{ $product->tax }}</td>
                                </tr>
                            @endif

                            @if ($settings->where('title', 'Product Discount')->where('options', 'yes')->count() > 0)
                                <tr>
                                    <th>Discount</th>
                                    <td>{{ $product->discount }}</td>
                                </tr>
                            @endif

                            @if (optional($product->product_stock)->opening_quantity)
                                <tr>
                                    <th>Opening Quantity</th>
                                    <td>{{ optional($product->product_stock)->opening_quantity }}</td>
                                </tr>
                            @endif

                            <tr>
                                <th>Warranty Days</th>
                                <td>{{ $product->warranty_days }} Days</td>
                            </tr>

                            <tr>
                                <th>Guarantee Days</th>
                                <td>{{ $product->guarantee_days }} Days</td>
                            </tr>

                            @if ($product->image)
                                <tr>
                                    <th>Product Image</th>
                                    <td>
                                        <img width="100" height="100" src="{{ asset($product->image) }}">
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('js')
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
@endsection
