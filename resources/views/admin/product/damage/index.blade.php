@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div>
                        @if(hasPermission('product-damages.create'))
                            <a href="{{ route('product-damages.create') }}" class="btn btn-primary pull-right">
                                <i class="fa fa-plus"></i>Add Damage
                            </a>
                        @endif
                        <h3><i class="fa fa-th-list"> </i> Damaged Product Information</h3>
                    </div>

                    <div class="tile-body table-responsive table-sm" style="height: 650px !important;">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th width="1%">#</th>
                                    <th>Date</th>
                                    <th>Reference</th>
                                    <th class="text-right">Amount</th>
                                    <th width="10%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($damages as $key => $damage)
                                    <tr>
                                        <td>{{ $key + $damages->firstItem() }}</td>
                                        <td>{{ $damage->date }}</td>
                                        <td>{{ $damage->reference }}</td>
                                        <td class="text-right">{{ $damage->total_amount }}</td>

                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                @include('admin.includes.user-log', ['data' => $damage])

                                                <a class="btn btn-sm btn-success" title="View Details" href="{{ route('product-damages.show', $damage->id) }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @if(hasPermission('product-damages.destroy'))
                                                    <a class="btn btn-sm btn-danger" title="Delete Sale"
                                                       href="javascript:void(0)"
                                                       data-toggle="modal" onclick="deleteData({{ $damage->id }})"
                                                       data-target="#DeleteModal">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @include('admin.includes.delete_confirm')
                            </tbody>

                        </table>
                        @include('admin.includes.pagination', ['data' => $damages])
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
            var url = '{{ route("product-damages.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>
@stop
