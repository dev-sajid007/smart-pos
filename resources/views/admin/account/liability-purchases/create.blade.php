@extends('admin.master')

@section('content')

    <main class="app-content">

        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <form class="form-horizontal" action="{{ route('liability-purchases.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="tile">
                        <h3 class="tile-title">Liability Purchase</h3>
                        <div class="tile-body" style="height: 740px !important;">

                            <table class="table table-sm" style="border: none">
                                <tr style="border: none">
                                    <td style="width: 20px; border:none">Liability</td>
                                    <td style="border: none">
                                        <select class="form-control select-liability select2">
                                            <option value="">Select Liability</option>
                                            @foreach ($liabilities as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="border: none">Particular</td>
                                    <td style="border: none">
                                        <textarea class="form-control particular" style="min-height: 60px" ></textarea>
                                    </td>
                                    <td style="border: none">Amount</td>
                                    <td style="border: none">
                                        <input type="text" class="amount form-control" onkeypress="return event.charCode >= 46 && event.charCode <= 57">
                                    </td>
                                    <td style="border: none">
                                        <button type="button" class="add-item form-control btn btn-dark" onclick="addLiability()"><i class="fa fa-plus"></i> Add</button>
                                    </td>
                                </tr>
                                
                            </table>
                            

                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sl.</th>
                                        <th>Liability</th>
                                        <th>Particular</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-center" width="10%">Action</th>
                                    </tr>
                                </thead>

                                <tbody class="item-body">

                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="3"><strong>Total: </strong></th>
                                        <th class="total-amount text-right"></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>


                            
                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <textarea class="form-control description" name="description" style="min-height: 50px" placeholder="Add a description"> </textarea>
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-fw fa-lg fa-save"></i> Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </main>
@endsection


@section('js')
    <script >
        $(document).ready(function() {
            $('.description').val('')
        })
        function addLiability()
        {
            let asset_id    = $('.select-liability option:selected').val()
            let asset_name  = $('.select-liability option:selected').text()
            let particular  = $('.particular').val()
            let amount      = $('.amount').val()

            if(asset_id != '' && particular != '' && amount != '') {
                let tr = `
                            <tr>
                                <td class="serial"></td>
                                <td class="liability-name">${ asset_name }
                                    <input type="hidden" name="particulars[]" value="${ particular }">
                                    <input type="hidden" class="input-amounts" name="amounts[]" value="${ amount }">
                                    <input type="hidden" class="liability-ids" name="liability_ids[]" value="${ asset_id }">
                                </td>
                                <td>${ particular }</td>
                                <td class="item-amount text-right">${ amount }</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="fa fa-times"></i></button>    
                                </td>
                            </tr>
                `
                $('.item-body').append(tr)
                setItemSerial()

                $('.select-liability').val('')
                $('.particular').val('')
                $('.amount').val('')

                $('.select2').select2()
            }

        }

        function setItemSerial()
        {
            let total = 0
            $('.serial').each(function(index) {
                $(this).text(index + 1)
                total += Number($(this).closest('tr').find('.item-amount').text() | 0)
            })
            $('.total-amount').text(total)
        }
    </script>
@stop
