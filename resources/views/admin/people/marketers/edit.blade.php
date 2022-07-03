@extends('admin.master')
@section('title', 'Marketer Create')

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <div class="tile">
                    <a href="{{route('marketers.index')}}" class="btn btn-primary pull-right float-right">
                        <i class="fa fa-eye"></i> View Marketers
                    </a>
                    <h3 class="tile-title">Add New Marketer</h3>

                    <div class="tile-body">
                        <hr>
                        <form class="form-horizontal pl-5" method="POST" enctype="multipart/form-data" action="{{ route('marketers.update',$marketer->id) }}">
                            @csrf @method('put')
                            <!-- Name -->
                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-2">Name</label>
                                <div class="col-md-6">
                                    <input name="marketers_name" value="{{ old('marketers_name')??$marketer->marketers_name }}" class="form-control" type="text" placeholder="Name">
                                    <div class="text-danger">
                                        {{ $errors->has('marketers_name') ? $errors->first('marketers_name'):'' }}
                                    </div>
                                </div>
                            </div>
                            <!-- Phone/Mobile -->
                            <div class="form-group row">
                                <label class="control-label col-md-2">Phone</label>
                                <div class="col-md-6">
                                    <input name="marketers_mobile" value="{{ old('marketers_mobile')??$marketer->marketers_mobile }}" class="form-control" type="text"
                                           onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Phone" maxlength="11"
                                           minlength="11">
                                    <div class="text-danger">
                                        {{ $errors->has('marketers_mobile') ? $errors->first('marketers_mobile'):'' }}
                                    </div>
                                </div>
                            </div>
                            <!-- Phone/Mobile -->
                            <div class="multiple-row form-group row">
                                <div class="col-md-8">
                                    <table  class="table table-sm table-bordered" id="table" width="100%">
                                        <thead>
                                            <tr>
                                                <td>Start Amount</td>
                                                <td>End Amount</td>
                                                <td>Parcentages</td>
                                                <td class="text-center" width="8%">Action</td>
                                            </tr>
                                        </thead>
                                        <tbody class="item-table-body">
                                            @foreach($marketers_details as $key => $details)
                                            <tr>
                                                <input type="hidden" class="details_id" name="details_id[]" value="{{ $details->id }}">
                                                <td>
                                                    <input name="start_amount[]" value="{{ old('start_amount')??$details->start_amount }}" class="form-control"
                                                        type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                        placeholder="Start Amount">
                                                    <div class="text-danger">
                                                        {{ $errors->has('start_amount') ? $errors->first('start_amount'):'' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <input name="end_amount[]" value="{{ old('end_amount')??$details->end_amount }}" class="form-control"
                                                        type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                        placeholder="End Amount">
                                                    <div class="text-danger">
                                                        {{ $errors->has('end_amount') ? $errors->first('end_amount'):'' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <input name="marketers_commission[]" value="{{ old('marketers_commission')??$details->marketers_commission }}" class="form-control" type="number"
                                                        onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="%" min="0" max="100">
                                                    <div class="text-danger">
                                                        {{ $errors->has('marketers_commission') ? $errors->first('marketers_commission'):'' }}
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm" title="Delete This Row" onclick="removeRow(this)">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-4">
                                    <div class='col-xs-12 col-sm-12'>
                                        <div class="float-left">
                                            <button class="btn btn-primary float-right btn-sm addmore" onclick="row_increment()" tabindex="-1" id="addMore" type="button">+ Add More </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tile-footer">
                                <div class="row">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-2">
                                        <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-fw
                                        fa-lg fa-check-circle"></i>Update Marketer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>


    <script type="text/javascript">
        $('.select2').select2();
    </script>
@endsection

@section('js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.js"></script>
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

<script>
// add new item row when click on add more button
function row_increment() {

    let i = $('table tr').length;
    let additional_items_field = ''
    let new_row =`<tr>
                    <td>
                        <input type="hidden" class="details_id" name="details_id[]" value="">
                        <input name="start_amount[]" value="{{ old('start_amount')??0 }}" class="form-control" type="number"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Start Amount">
                        <div class="text-danger">
                            {{ $errors->has('start_amount') ? $errors->first('start_amount'):'' }}
                        </div>
                    </td>
                    <td>
                        <input name="end_amount[]" value="{{ old('end_amount')??0 }}" class="form-control" type="number"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="End Amount">
                        <div class="text-danger">
                            {{ $errors->has('end_amount') ? $errors->first('end_amount'):'' }}
                        </div>
                    </td>
                    <td>
                        <input name="marketers_commission[]" value="{{ old('marketers_commission') }}" class="form-control" type="number"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="%" min="0" max="100">
                        <div class="text-danger">
                            {{ $errors->has('marketers_commission') ? $errors->first('marketers_commission'):'' }}
                        </div>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" title="Delete This Row" onclick="removeRow(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
    $('.item-table-body').append(new_row);
    i++;
    setItemSerial()
}
function setItemSerial()
{
    $('.item-table-body tr').each(function (counter) {
        $(this).find('.item-serial-counter').text(counter + 1)
    });
}
function removeRow(el) {
    var ids = $('.details_id').val();

              $('.delete_ids').val(ids);
    $(el).parents("tr").remove()
    setItemSerial()
}
</script>
@stop
