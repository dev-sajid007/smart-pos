@extends('admin.master')

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="row">
                        <div class="col-4">
                            <h3><i class="fa fa-th-list"> </i> Purchase Return Receive List</h3>
                        </div>

                            <div class="col-4 offset-md-2">
                                <select class="form-control sale_id" name="sale_id" id="">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-2">
                                <a href="{{ route('purchase-return-receives.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Create New</a>
                            </div>
                    </div>
                    <br>


                    <div class="tile-body table-responsive table-sm">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th width="3%">Sl.</th>
                                    <th>Date</th>
                                    <th>Return Invoice</th>
                                    <th>Supplier</th>
                                    <th>Comment</th>
                                    <th class="text-center">Total Quantity</th>
                                    <th width="7%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($purchaseReturnReceives as $key => $returnReceive)
                                    <tr>
                                        <td>{{ $key + $purchaseReturnReceives->firstItem() }}</td>
                                        <td>{{ $returnReceive->date }}</td>
                                        <td>{{ $returnReceive->purchase_return->invoice_id }}</td>
                                        <td>{{ $returnReceive->supplier->name }}</td>
                                        <td>{{ $returnReceive->comment }}</td>
                                        <td class="text-center">{{ $returnReceive->total_quantity }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                <span class="btn btn-info btn-sm popover-success"
                                                      data-toggle="popover"
                                                      data-placement="top"
                                                      title="<i class='ace-icon fa fa-info-circle text-success'></i> Log Information"
                                                      data-content="<p>Created By: {{ ucwords(optional($returnReceive->created_user)->name) ?? 'System Admin' }}.</p> <p> Created At : {{ fdate($returnReceive->created_at, 'Y-m-d  h:i A') }} </p>">
                                                    <i class="fa fa-info-circle"></i>
                                                </span>

                                                <a href="{{ route('purchase-return-receives.show', $returnReceive->id) }}" title="View" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="#" data-toggle="modal" onclick="deleteData({{ $returnReceive->id }})" data-target="#DeleteModal" title="Delete" target="_blank" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                @include('admin.includes.delete_confirm')

                            </tbody>
                        </table>
                        @include('admin.includes.pagination', ['data' => $purchaseReturnReceives])
                    </div>
                </div>
            </div>
        </div>
    </main>






@endsection

@section('footer-script')
    <script type="text/javascript">
        function deleteData(id) {
            var id = id;
            var url = '{{ route("purchase-return-receives.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>

    <script>
        $(function(){
            $('.sale_id').select2({
                placeholder: 'Enter Invoice ID/Reference/Date',
                minimumInputLength: 1,
                ajax: {
                    url: '/get-all-sales',
                    dataType: 'json',
                    quietMillis: 250,
                    data: params => {
                        return {
                            name: params.term,
                        }
                    },
                    processResults: (data) => {
                        return {
                            results: $.map(data, (item, index) => {
                                // console.log()
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                },

            });
        })
    </script>
@stop
