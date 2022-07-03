@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                @if(Session::get('message'))
                    <div class="alert alert-success">
                        {{ Session::get('message') }}
                    </div>
                @endif

                <div class="tile">
                    <div class="d-flex">
                        <h3><i class="fa fa-th-list"></i> {{ ucfirst(request('type')) }} Voucher List</h3>

                        @if(hasPermission(['debit.vouchers.create', 'credit.vouchers.create']))
                            <a href="{{ route('payments.create', ['type'=>request('type')]) }}" class="btn btn-primary mr-0 ml-auto">
                                <i class="fa fa-plus"></i> Add {{ ucfirst(request('type')) }} Voucher
                            </a>
                        @endif
                    </div>

                    <!-- filter -->
                    <div class="row">
                        <div class="col-sm-8 mx-auto">
                            <form action="" method="get">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Search Voucher</span>
                                    </div>
                                    <input class="form-control form-control-md" name="search" placeholder="Search anything.........." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button style="cursor: pointer" type="submit" class="input-group-text btn-sm"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>




                    <div class="tile-body table-responsive">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th>Party</th>
                                    <th>Phone</th>
                                    <th>Voucher Date</th>
                                    <th class="text-right">Paid Amount</th>
                                    <th width="10%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($vouchers as $key => $voucher)
                                    <tr>
                                        <td width="1%">{{++$key}}</td>
                                        <td>{{ $voucher->party->name }}</td>
                                        <td>{{ $voucher->party->phone }}</td>
                                        <td>{{ $voucher->voucher_date->format('Y-m-d') }}</td>
                                        <td class="text-right">{{ number_format($voucher->paid_amount, 2) }}</td>

                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                @include('admin.includes.user-log', ['data' => $voucher])
                                                <a class="btn btn-primary btn-sm" title="View"
                                                   href="{{ route('payments.show', ['id'=>$voucher->id,'type'=> request('type')]) }}">
                                                    <i class="fa fa-eye"></i> </a>

                                                @if(hasPermission(['debit.vouchers.destroy', 'credit.vouchers.destroy']))
                                                    <button type="button" data-toggle="modal" onclick="deleteData({{ $voucher->id }})" data-target="#DeleteModal" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                                                @endif
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $vouchers])
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('admin.includes.delete_confirm')

    <form action="" id="deleteItemForm" method="POST">
        @csrf @method("DELETE")
    </form>

    <script type="text/javascript">
        function deleteData(id) {
            var id = id;
            var url = '{{ route("payments.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>
@endsection
