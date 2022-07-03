@extends('admin.master')
@section('title', ' - Fund Transfer')

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    @if(hasPermission('fund-transfers.create'))
                        <a href="{{ route('fund-transfers.create') }}" class="btn btn-primary pull-right d-print-none"><i class="fa fa-plus"></i> New Transfer</a>
                    @endif
                    <h3 class="d-print-none"><i class="fa fa-th-list"></i> Fund Transfer List</h3>


                    <!-- Filter -->
                    @include('admin.account.fund-transfers.filter-fund')

                    <div class="tile-body table-responsive" style="min-height: 740px !important;">


                        @include('partials._alert_message')

                        <table class="table table-hover table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Date</th>
                                    <th>Account From</th>
                                    <th>Account To</th>
                                    <th class="text-right">Amount</th>
                                    <th>Reference No</th>
                                    <th>Comment</th>
                                    <th width="10%" class="text-center d-print-none">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($fundTransfers as $key => $fundTransfer)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $fundTransfer->date }}</td>
                                        <td>{{ $fundTransfer->fromAccount->account_name }}</td>
                                        <td>{{ $fundTransfer->toAccount->account_name }}</td>
                                        <td class="text-right">{{ number_format($fundTransfer->amount, 2) }}</td>
                                        <td>{{ $fundTransfer->reference_no }}</td>
                                        <td>{{ $fundTransfer->comment }}</td>

                                        <td class="text-center d-print-none">
                                            <div class="btn-group btn-corner">
                                                @include('admin.includes.user-log', ['data' => $fundTransfer])

                                                @if($fundTransfer->image)
                                                    <button type="button" title="View reference image" onclick="showFundTransferImage('{{ asset($fundTransfer->image) }}')" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#imageViewModal">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                @endif
                                                @if (hasPermission('fund-transfers.destroy'))
                                                    <button type="button" onclick="delete_item(`{{ route('fund-transfers.destroy', $fundTransfer->id) }}`)" class="btn btn-danger btn-sm" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal -->
    @include('admin.account.fund-transfers.display-image-view')


    <!-- delete form -->
    @include('partials._delete_form')
@endsection

@section('footer-script')

    <script type="text/javascript">
        function showFundTransferImage(imagePath) {
            $('#reference-image').attr('src', imagePath)
        }
    </script>

@endsection
