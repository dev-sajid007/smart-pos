@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-th-list"></i> Account Reviews</h1>
            </div>

            <a href="{{url('/customer-collections/create')}}" class="btn btn-primary" style="float: right;"><i class="fa
            fa-plus"></i>Review Account</a>

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body table-responsive table-sm">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th width="1%">#</th>
                                <th>Invoice ID</th>
                                <th>Date</th>
                                <th>Customer Name</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th width="5%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($sl = $reviews->firstItem())
                            @foreach ($reviews as $review)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $review->id }}</td>
                                    <td>{{ $review->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $review->transactionable->name }}</td>
                                    <td>{{ $review->type === 'advance' ? 'Advance Receive' : 'Receivable Due' }}</td>
                                    <td>{{$review->amount}}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm"
                                           href="{{ route('customer-collections.show', $review->id) }}"><i
                                                    class="fa fa-eye"></i></a>
                                        <a class="btn btn-danger btn-sm" href="#" data-toggle="modal"
                                           onclick="deleteData({{ $review->id }})"
                                           data-target="#DeleteModal"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            @include('admin.includes.delete_confirm')

                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $reviews])
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
            var url = '{{ route("customer-collections.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>
@stop