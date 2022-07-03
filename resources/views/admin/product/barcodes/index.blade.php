
@extends('admin.master')
@section('title', 'Barcode List')

@section('content')
    <main class="app-content">
        <!-- Breadcrumb -->
        <div class="app-title">
            <div>
                <h1><i class="fa fa-th-list"></i> Barcode Information</h1>
                <p>Barcode information </p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Barcode Information</li>
                <li class="breadcrumb-item active"><a href="#">Barcode Information Table</a></li>
            </ul>
        </div>


        <div class="row">
            <div class="col-md-12">
                @if(Session::get('message'))
                    <div class="alert alert-success">
                        {{ Session::get('message') }}
                    </div>
                @endif

                <div class="tile">
                    <a href="{{ route('barcodes.create') }}" class="btn btn-primary" style="float: right;"><i class="fa fa-plus"></i> Create Barcode</a>
                    <h3 class="tile-title"><i class="fa fa-list"></i> Barcode List </h3>



                    <!-- filter -->
                    <div class="row">
                        <div class="col-sm-8 mx-auto">
                            <form action="" method="get">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Search Product</span>
                                    </div>
                                    <input class="form-control form-control-md" name="search" placeholder="Search by product name or barcode" value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button style="cursor: pointer" type="submit" class="input-group-text btn-sm"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="tile-body table-responsive" style="min-height: 540px">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th>Product Name</th>
                                    <th class="text-center">Barcode</th>
                                    <th title="Product Quantity" class="text-center">Quantity</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($barcodes as $key => $productBarcode)
                                    <tr>
                                        <td>{{ $key + $barcodes->firstItem() }}</td>
                                        <td>{{ optional($productBarcode->product)->product_name }}</td>
                                        <td class="text-center">
                                            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($productBarcode->barcode_number, "C128") }}" alt="barcode" style="width: 160px; height: 60px;"/>
                                            <br>
                                            {{ $productBarcode->barcode_number }}
                                        </td>
                                        <td class="text-center">{{ optional($productBarcode->product)->product_stock['available_quantity'] }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a class="btn btn-primary btn-sm" title="Barcode Print" href="{{ route('barcodes.show', $productBarcode->id) }}?type=normal"><i class="fa fa-print"></i></a>
                                                <a class="btn btn-success btn-sm" title="Barcode Label Print" href="{{ route('barcodes.show', $productBarcode->id) }}?type=label"><i class="fa fa-print"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $barcodes])
                    </div>
                </div>
            </div>
        </div>
    </main>


    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>

    <script type="text/javascript">
        function deleteData(id)
        {
            var id = id;
            var url = '{{ route("sales.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit()
        {
            $("#deleteForm").submit();
        }
    </script>

    @include('admin.includes.delete_confirm')

@endsection
