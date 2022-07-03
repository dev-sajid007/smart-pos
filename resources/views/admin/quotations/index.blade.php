@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    @if(hasPermission('quotations.create'))
                    <a href="{{route('quotations.create')}}" class="btn btn-primary" style="float: right;"><i
                            class="fa fa-eye"></i> Add Quotation</a>
                    @endif
                    <h3 class="tile-title">Quotation List </h3>
                    <div class="tile-body table-responsive">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reference</th>
                                <th>Customer</th>
                                <th>Total Payable</th>
                                {{--<th>Quotation Status</th>--}}
                                <th width="10%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($quotations as $quotation)
                                <tr>
                                    <td>{{ $quotation->quotation_date }}</td>
                                    <td>{{ $quotation->quotation_reference }}</td>
                                    <td>{{ $quotation->quotation_customer->name }}</td>
                                    <td>{{ $quotation->total_payable }}</td>
                                    {{--<td>{{ $quotation->quotation_status->name }}</td>--}}
                                    <td>
                                        <a href="{{ url('quotations/invoice', $quotation->id) }}"
                                           class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
                                        @if($quotation->fk_status_id != 1)
                                            @if(hasPermission('quotations.edit'))
                                            <a href="{{ route('quotations.edit', $quotation->id) }}"
                                               class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                            @endif
                                            @if(hasPermission('quotations.destroy'))
                                            <a href="#" class="btn btn-sm btn-danger" data-toggle="modal"
                                               onclick="deleteData({{ $quotation->id }})"
                                               data-target="#DeleteModal"><i class="fa fa-trash"></i></a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @include('admin.includes.delete_confirm')

                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $quotations])
                    </div>
                </div>
            </div>
        </div>
    </main>


    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>

    <script type="text/javascript">
        function deleteData(id) {
            var id = id;
            var url = '{{ route("quotations.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>



@endsection
