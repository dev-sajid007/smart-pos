@extends('admin.master')

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div>
                        <h3 class="float-left"><i class="fa fa-th-list"></i> {{ ucfirst(request('type')) }} Payment List</h3>

                        @if(hasPermission('collections.create'))
                            <a href="/due-collections/create?type={{ request('type') }}" class="btn btn-primary float-right" ><i class="fa fa-plus"></i>  Add new </a>
                        @endif
                    </div>

                    <div class="tile-body table-responsive table-sm">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th width="1%">#</th>
                                    <th width="10%">Invoice ID</th>
                                    <th width="10%">Date</th>
                                    <th>Customer Name</th>
                                    <th>Account</th>
                                    <th>Payment Method</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th width="1%">Approval</th>
                                    <th width="5%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($reviews as $key => $review)
                                    <tr>
                                        <td>{{ $key + $reviews->firstItem() }}</td>
                                        <td>{{ $review->invoiceId }}</td>
                                        <td>{{ $review->date }}</td>
                                        <td>{{ optional($review->transactionable)->name }}</td>
                                        <td>{{ optional($review->account)->account_name }}</td>
                                        <td>{{ ucwords(optional($review->payment_method)->method_name) }}</td>
                                        <td>{{ $review->isSupplier() ? 'Payment' : 'Receive' }}</td>
                                        <td>{{ number_format(abs($review->amount),2) }}</td>
                                        <td>
                                            @if (!$review->approved_at)
                                                <form action="{{ route('due-collections.approve', $review->id) }}"
                                                      onsubmit="disableButton(this)"
                                                      method="post">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                </form>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                <span class="btn btn-info btn-sm popover-success"
                                                      data-toggle="popover"
                                                      data-trigger="hover"
                                                      data-placement="top"
                                                      title="<i class='ace-icon fa fa-info-circle text-success'></i> Log Information"
                                                      data-content="<p>Created By: {{ ucwords(optional($review->created_user)->name) ?? 'System Admin' }}.</p> <p> Created At : {{ fdate($review->created_at, 'Y-m-d  h:i A') }} </p>
                                                       <hr/>
                                                       <p>Approved By: {{ ucwords(optional($review->approved_user)->name) ?? 'System Admin' }}.</p> <p> Updated At : {{ fdate($review->approved_at, 'Y-m-d  h:i A') }} </p>">
                                                    <i class="fa fa-info-circle"></i>
                                                </span>

                                                <a class="btn btn-primary btn-sm" href="{{ route('due-collections.show', $review->id) }}?type={{ request('type') }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                @if(hasPermission('collections.destroy'))
                                                    <a class="btn btn-danger btn-sm" href="#" data-toggle="modal" onclick="deleteData({{ $review->id }})" data-target="#DeleteModal">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $reviews])
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('admin.includes.delete_confirm')
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
        function disableButton(element){
            $(element).find('button').attr('disabled', true)
        }
    </script>
@stop
