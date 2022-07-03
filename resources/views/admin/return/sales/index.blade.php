@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div>
                        @if(hasPermission('sale.products.return'))
                            <a href="{{ route('sale-returns.create') }}" class="btn btn-primary pull-right"><i class="fa
                          fa-plus"></i> Add Return</a>
                        @endif
                        <h3><i class="fa fa-th-list"> </i> Sale Return Info</h3>
                    </div>

                    <div class="tile-body table-responsive table-sm">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th width="1%">#</th>
                                    <th>Invoice ID</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Reference</th>
                                    <th>Comment</th>
                                    <th>Amount</th>
                                    <th width="5%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php $sl = 1 @endphp

                                @foreach ($randomReturns as $return)
                                    <tr>
                                        <td>{{ $sl++ }}</td>
                                        <td>{{ $return->invoiceId }}</td>
                                        <td>{{ $return->date->format('d-m-Y') }}</td>
                                        <td>{{ $return->customer->name}}</td>
                                        <td>{{ $return->reference}}</td>
                                        <td>{{ $return->comment}}</td>
                                        <td>{{ $return->amount}}</td>

                                        <td class="text-center">

                                            <div class="btn-group btn-corner">
                                                @include('admin.includes.user-log', ['data' => $return])

                                                <a class="btn btn-sm btn-primary" title="Pos Print" href="{{ url("/sale-returns/{$return->id}") }}">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                <a class="btn btn-sm btn-danger" title="Delete Sale" href="#"
                                                   data-toggle="modal" onclick="deleteData({{ $return->id }})"
                                                   data-target="#DeleteModal"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach



                                @foreach ($saleReturns as $return)
                                    <tr>
                                        <td>{{ $sl++ }}</td>
                                        <td>{{ $return->invoice_no }}</td>
                                        <td>{{ $return->date->format('d-m-Y') }}</td>
                                        <td>{{ $return->customer->name }}</td>
                                        <td>{{ $return->reference }}</td>
                                        <td>{{ $return->comment }}</td>
                                        <td>{{ $return->amount }}</td>

                                        <td class="text-center">

                                            <div class="btn-group btn-corner">
                                                @include('admin.includes.user-log', ['data' => $return])

                                                <a class="btn btn-sm btn-primary" title="Pos Print" href="{{ url("/sale-returns/{$return->id}") }}">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                <a class="btn btn-sm btn-danger" title="Delete Sale" href="#"
                                                   data-toggle="modal" onclick="deleteData({{ $return->id }})"
                                                   data-target="#DeleteModal"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                @include('admin.includes.delete_confirm')

                            </tbody>

                        </table>
                        @include('admin.includes.pagination', ['data' => $saleReturns])

                    </div>
                </div>
            </div>
        </div>

    </main>






@endsection

@section('footer-script')
    <script type="text/javascript">
        function deleteData(id) {
            $("#deleteForm").attr('action', `/sale-returns/${id}`);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>
@stop
