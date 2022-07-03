@extends('admin.master')
@section('title', 'Barcode Create')

@section('content')

    <main class="app-content">
        <form class="form-horizontal" method="POST" action="{{ route('barcodes.store') }}">
            @csrf

            <!-- Breadcrumb -->
            <div class="app-title">
                <div>
                    <h1><i class="fa fa-edit"></i> Barcode </h1>
                    <p>Create Barcode Form</p>
                </div>
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                    <li class="breadcrumb-item">Barcode </li>
                    <li class="breadcrumb-item"><a href="#">Add Barcode</a></li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    @include('partials._alert_message')

                    <div class="bs-componen">
                        <div class="tile">
                            <a href="{{ route('barcodes.index') }}" class="btn btn-primary" style="float: right;"><i class="fa fa-list"></i> Create List</a>
                            <h4 class="tile-title"><i class="fa fa-plus"></i> Generate Barcode in Product</h4>



                            <div class="tile-body" style="min-height: 540px !important;">

                                <div class='row'>

                                    <div class="col-sm-4">
                                        <label>Product</label>
                                        <select class="form-control select-product select2">
                                            <option value="">Select</option>
                                            @foreach ($products as $key => $product)
                                                <option value="{{ $product->id }}" data-barcode="{{ $product->barcode_number }}">{{ ucfirst($product->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="button" class="btn btn-primary add-item" style="margin-top: 30px !important; margin-bottom: 20px"><i class="fa fa-plus"></i> Add <Products></Products></button>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered col-md-12" id="table">
                                                <thead>
                                                    <tr>
                                                        <th>Sl.</th>
                                                        <th>Product Name</th>
                                                        <th style="display: none">Product ID</th>
                                                        <th>Barcode</th>
                                                        <th>Generate</th>
                                                        <th class="text-center" width="40px">Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>

                                                    @if (old() == true)
                                                        @foreach(old('product_ids') as $key => $value)
                                                            <tr>
                                                                <td class="item-serial">{{ $key + 1 }}</td>
                                                                <td>
                                                                    <input type="hidden" name="product_ids[]" value="{{ old('product_ids')[$key] }}">
                                                                    <input type="hidden" name="product_names[]" value="{{ old('product_names')[$key] }}">
                                                                    {{ old('product_names')[$key] }}
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="barcode_numbers[]" value="{{ old('barcode_numbers')[$key] }}" class="barcode-number form-control">
                                                                </td>
                                                                <td>
                                                                    <button type="button" id="getBarcode_1" onclick="getBarcode(this)" title="Generate New Barcode" class="btn btn-sm btn-info btn-block">Generate</button>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="btn-group mr-2">
                                                                        <button type="button" class="btn btn-danger btn-sm" title="Delete This Row" onclick="removeRow(this)"><i class="fa fa-times"></i></button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>

                                                <tfoot>
                                                    <tr>
                                                        <th colspan="5" class="text-right">
                                                            <button class="btn btn-success btn-md px-5 save-btn" type="submit">Save</button>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <br>
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
    <script type="text/javascript">

        $(document).ready(function () {
            $('.save-btn').attr('disabled', true)
            $(el).parents("tr").remove()
            setItemSerial()
        })

        $('.add-item').click(function () {
            let selected = $('.select-product option:selected')
            let barcode = selected.data('barcode')
            let product_id = selected.val()
            let product_name = selected.text()

            let row = `<tr>
                            <td class="item-serial"></td>
                            <td>
                                <input type="hidden" name="product_ids[]" value="${ product_id }">
                                <input type="hidden" name="product_names[]" value="${ product_name }">`
                            + product_name +
                            `</td>
                            <td>
                                <input type="text" name="barcode_numbers[]" value="${ barcode }" class="barcode-number form-control">
                            </td>
                            <td>
                                <button type="button" id="getBarcode_1" onclick="getBarcode(this)" title="Generate New Barcode" class="btn btn-sm btn-info btn-block">Generate</button>
                            </td>
                            <td class="text-center">
                                <div class="btn-group mr-2">
                                    <button type="button" class="btn btn-danger btn-sm" title="Delete This Row" onclick="removeRow(this)"><i class="fa fa-times"></i></button>
                                </div>
                            </td>
                        </tr>`

            if (product_id != '') {
                $('table').append(row);
                // disable selected option
                $('.select-product option[value=' + product_id + ']').prop("disabled", true);
                $('.select-quantity').val('');
                $('.select-product').val('');
                $('.select-product').select2('focus');
                $('.select-product').select2();
                setItemSerial()
            }
        })

        function removeRow  (el) {
            $(el).parents("tr").remove()
            setItemSerial()
        }

        function getBarcode(object) {
            let barcode = Math.floor(Math.random() * (10000000 + 1) + 10000000);

            $(object).closest('tr').find('.barcode-number').val(barcode)
        }

        function setItemSerial() {

            $('.save-btn').attr('disabled', true)

            $('.item-serial').each(function (index) {
                $(this).text(index+1)
                $('.save-btn').attr('disabled', false)
            })
        }
    </script>

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
