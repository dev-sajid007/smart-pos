@extends('admin.master')
@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')
                <div class="tile">
                    <a href="{{route('product-damages.index')}}" class="btn btn-primary pull-right" style="float: right;"><i
                                class="fa fa-eye"></i> View Damages</a>
                    <h3 class="tile-title">New Product Damage</h3>
                    <hr>
                    <div class="tile-body">
                        <form class="form-horizontal" method="POST" action="{{ route('product-damages.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Reference No</label>
                                        <div class="col-md-8">
                                            <input name="reference" value="{{ old('reference') }}"
                                                   class="form-control" type="text" placeholder="Reference">
                                            <div class="text-danger">
                                                {{ $errors->has('reference') ? $errors->first('reference'):'' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3">Date</label>
                                        <div class="col-md-8">
                                            <input name="date" value="{{ old('date') ?? date('Y-m-d') }}"
                                                   class="form-control dateField" type="text" placeholder="Date">
                                            <div class="text-danger">
                                                {{ $errors->has('date') ? $errors->first('date') : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <td width="30%">Product Name</td>
                                                <td width="10%">Available</td>
                                                <td>Description</td>
                                                <td width="5%">Type</td>
                                                <td width="15%">Damaged Quantity</td>
                                                <td width="1%">Action</td>
                                            </tr>
                                        </thead>

                                        <tbody class="r-group">
                                        <tr class="r-row">
                                            <td>
                                                <select class="form-control product_name" name="product_id[]"></select>
                                            </td>
                                            <td>
                                                <input type="text" name="available_qty" class="form-control
                                                available-quantity" value="0" placeholder="Available Quantity" readonly>
                                            </td>
                                            <td>
                                                <textarea name="description[]" class="form-control"
                                                          placeholder="Description"></textarea>
                                            </td>
                                            <td>
                                                <select name="type[]" style="height: 33px">
                                                    <option value="expired">Expired</option>
                                                    <option value="damaged">Damaged</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input value="0" onkeyup="checkQuantity(this, event)"
                                                       class="form-control"
                                                       name="quantity[]" type="text"
                                                       placeholder="Damaged Quantity">
                                            </td>
                                            <td>
                                                <button type="button" onclick="addRow()" tabindex="-1" class="btn
                                                btn-primary btn-sm">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>

                                    </table>
                                    <div class="tile-footer">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button class="btn btn-primary pull-right" type="submit" type="submit"><i
                                                            class="fa fa-fw fa-lg fa-check-circle"></i>Add Damage
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('footer-script')
    <script src="{{asset('jq/select2Loads.js')}}"></script>
    <script>
        function triggerSelect(){

            select2Loads({
                selector: '.product_name',
                url: '/saleable-products'
            })

        }
        triggerSelect()



        function addRow()
        {
            const htmlString = `
            <tr class="r-row">
                <td>
                    <select class="form-control product_name" name="product_id[]">
                    </select>
                </td>
                <td>
                    <input type="text" name="available_qty" class="form-control available-quantity" value="0"
                           placeholder="Available Quantity" readonly>
                </td>
                <td>
                    <textarea name="description[]" class="form-control" placeholder="Description"></textarea>
                </td>
                <td>
                    <select name="type[]" style="height: 33px">
                        <option value="expired">Expired</option>
                        <option value="damaged">Damaged</option>
                    </select>
                </td>
                <td>
                    <input value="0" onkeyup="checkQuantity(this, event)" class="form-control" name="quantity[]"
                    type="text" placeholder="Damaged Quantity"">
                </td>
                <td>
                    <button type="button" onclick="deleteRow(this)" class="btn btn-danger btn-sm"><i class="fa
                    fa-trash"></i></button>
                </td>
            </tr>
            `;
            $('.r-group').append(htmlString);
            triggerSelect()
            setAvailableQuantity()

        }

        function deleteRow(element)
        {
            $(element).parents('.r-row').remove()
        }

    </script>

    <script>
        function setAvailableQuantity()
        {
            $('.product_name').change(function (e) {
                if (parseFloat(e.target.value)) {
                    $.get(`/sales/available_quantity/${e.target.value}`, function (data) {
                        console.log(data)
                        $(e.target).parents('.r-row').find('.available-quantity').val(data.available_quantity)
                    })
                }
            })
        }
        setAvailableQuantity()


        function checkQuantity(element, event){
            if(parseFloat(event.keyCode) === 13){
                addRow()
            }


            let available = parseFloat($(element).parents('.r-row').find('.available-quantity').val());
            let quantity = parseFloat($(element).val());
            if(!quantity) quantity = 0;

            if (quantity > available){
                $(element).val(available)
            }
        }

        $(document).on('keypress', 'input', function(e) {
            return e.which !== 13;
        });


    </script>
@endsection
