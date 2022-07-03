@extends('admin.master')

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="row">
                        <div class="col-3">
                            <h3><i class="fa fa-th-list"> </i> Purchase Return List</h3>
                        </div>

                            <div class="col-4 offset-md-3">
                                <select class="form-control sale_id" name="sale_id" id="">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-2">
                                <a href="{{ route('purchase-returns.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> New Return</a>
                            </div>
                    </div>
                    <br>


                    <div class="tile-body table-responsive table-sm">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th width="3%">Sl.</th>
                                    <th>Invoice Id</th>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                    <th>Reference</th>
                                    <th>Comment</th>
                                    <th class="text-right">Amount</th>
                                    <th width="7%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($purchaseReturns as $key => $purchaseReturn)
                                    <tr>
                                        <td>{{ $key + $purchaseReturns->firstItem() }}</td>
                                        <td>{{ $purchaseReturn->invoice_id }}</td>
                                        <td>{{ $purchaseReturn->date }}</td>
                                        <td>{{ $purchaseReturn->supplier->name }}</td>
                                        <td>{{ $purchaseReturn->reference }}</td>
                                        <td>{{ $purchaseReturn->comment }}</td>
                                        <td class="text-right">{{ number_format($purchaseReturn->total_amount, 2) }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                @include('admin.includes.user-log', ['data' => $purchaseReturn])

                                                <a href="{{ route('purchase-returns.show', $purchaseReturn->id) }}" title="View" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="#" data-toggle="modal" onclick="deleteData({{ $purchaseReturn->id }})" data-target="#DeleteModal" title="Delete" target="_blank" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                @include('admin.includes.delete_confirm')

                            </tbody>
                        </table>
                        @include('admin.includes.pagination', ['data' => $purchaseReturns])
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
            var url = '{{ route("purchase-returns.destroy", ":id") }}';
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
