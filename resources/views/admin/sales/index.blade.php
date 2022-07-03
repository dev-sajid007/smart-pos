
@extends('admin.master')

@section('title', ' - Sale')

@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div>
                        @if(hasPermission('sales.create'))
                        <a href="{{route('sales.create')}}" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Add
                            Sales</a>
                        @endif
                        <h3><i class="fa fa-th-list"></i> Sale Information</h3>

                        <!-- filter -->
                        <div class="row">
                            <div class="col-sm-8 mx-auto">
                                <form action="" method="get" style="width: 100%">
                                    <table style="width: 100%">
                                        <tr>
                                            <th width="40%">Customer</th>
                                            <th width="40%">Invoice Id</th>
                                            <th width="20%">Action</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select class="form-control select2" name="customer_id">
                                                    <option value="">All</option>
                                                    @foreach ($customers as $id => $customer)
                                                        <option value="{{ $id }}" {{ $id == request('customer_id') ? 'selected' : '' }}>{{ $customer }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" name="invoice_id" class="form-control" value="{{ request('invoice_id') }}"></td>
                                            <td>
                                                <button style="cursor: pointer" title="Click for Search Result" type="submit" class="btn btn-info">
                                                    <i class="fa fa-search" style="margin-right: 5px"></i> <strong>Search</strong>
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>


                    <br>


                    <div class="tile-body table-responsive table-sm">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th width="1%">#</th>
                                    <th width="10%">Invoice ID</th>
                                    <th width="8%">Date</th>
                                    <th>Customer</th>
                                    <th class="text-right" width="13%">Payable Amount</th>
                                    <th class="text-right" width="13%">Paid Amount</th>
                                    <th class="text-right" width="10%">Due</th>
                                    <th class="text-right" width="10%">Change</th>
                                    <th width="12%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($sales as $key => $sale)
                                    <tr>
                                        <td>{{ $key + $sales->firstItem() }}</td>
                                        <td>{{ ($sale->sale_invoice_id) ? '#S-'.$sale->sale_invoice_id : $sale->invoiceId }}</td>
                                        <td>{{ $sale->sale_date->format('d-m-Y') }}</td>
                                        <td>{{ optional($sale->sale_customer)->name }}</td>
                                        <td class="text-right">{{ number_format($sale_total = $sale->total_sale_amount + ($sale->cod_amount ?? 0) + $sale->currier_amount - $sale->invoice_discount, 2) ?? 0.00 }}</td>
                                        <td class="text-right">{{ number_format($sale->paid, 2) }}</td>
                                        <td class="text-right">{{ number_format($sale_total - $sale->paid, 2) }}</td>
                                        <td class="text-right">{{ number_format($sale->change_amount, 2) }}</td>

                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                @include('admin.includes.user-log', ['data' => $sale])

                                                {{-- <a class="btn btn-sm btn-primary" title="Normal Print" target="_blank" href="{{ route('sales.show', $sale->id) }}?print_type=invoice">
                                                    <i class="fa fa-eye"></i>
                                                </a> --}}
                                                <a class="btn btn-sm btn-warning" title="Invoice Print" target="_blank" href="{{ route('sales.show', $sale->id) }}?print_type=invoice">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                {{-- @if($sale->sale_type != 'Package' && $sale->sale_details->sum('serials_count') == 0) --}}
                                                @if($sale->sale_type != 'Package')
                                                    <a class="btn btn-sm btn-success text-light" title="Edit Sale" href="{{ route('sales.edit', $sale->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif
                                                @if (optional($sale->sale_customer)->email && $isSendMail)
                                                    <a class="btn btn-sm btn-purple" title="Send Invoice To Email" href="{{ route('sales.send-message', $sale->id) }}?type=email">
                                                        <i class="fa fa-envelope"></i>
                                                    </a>
                                                @endif

                                                @if (optional($sale->sale_customer)->phone && $isSendSms)
                                                    <a class="btn btn-sm btn-ass" title="Send Invoice Message" href="{{ route('sales.send-message', $sale->id) }}?type=sms">
                                                        <i class="fa fa-telegram"></i>
                                                    </a>
                                                @endif

                                                @if(hasPermission('sales.destroy'))
                                                    <a class="btn btn-sm btn-danger" title="Delete Sale" href="#"
                                                       data-toggle="modal" onclick="deleteData({{ $sale->id }})"
                                                       data-target="#DeleteModal"><i class="fa fa-trash"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            {{-- <div class="btn-group" role="group" aria-label="Button group with nested dropdown">--}}
                                            {{--     <div class="btn-group" role="group">--}}
                                            {{--         <button class="btn btn-primary btn-sm dropdown-toggle"--}}
                                            {{--                 id="btnGroupDrop3"--}}
                                            {{--                 type="button" data-toggle="dropdown" aria-haspopup="true"--}}
                                            {{--                 aria-expanded="false">Action </button>--}}
                                            {{--         <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"--}}
                                            {{--              style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(36px, 36px, 0px);">--}}
                                            {{--             <a class="dropdown-item"--}}
                                            {{--                href="{{ url('sales/invoice', $sale->id)}}">--}}
                                            {{--                 <i class="fa fa-eye"></i> Normal Print--}}
                                            {{--             </a                                            >--}}
                                            {{--             <a class="dropdown-item" title="Pos Print"--}}
                                            {{--                href="{{ url('sales/pos-invoice', $sale->id) }}">--}}
                                            {{--                 <i class="fa fa-print"></i>  POS Print</a>--}}
                                            {{--             <a class="dropdown-item" title="Edit Sale"--}}
                                            {{--                href="{{ route('sales.edit', $sale->id) }}">--}}
                                            {{--                 <i class="fa fa-edit"></i>Edit</a                                          >--}}
                                            {{--             @if(hasPermission('sales.destroy'))--}}
                                            {{--             <a class="dropdown-item" title="Delete Sale"--}}
                                            {{--                href="#"--}}
                                            {{--                data-toggle="modal" onclick="deleteData({{ $sale->id }})"--}}
                                            {{--                data-target="#DeleteModal"><i class="fa fa-trash"></i>Delete</a>--}}
                                            {{--             @endif--}}
                                            {{--         </div>--}}
                                            {{--     </div>--}}
                                            {{-- </div>--}}
                                        </td>
                                    </tr>
                                @endforeach

                                @include('admin.includes.delete_confirm')
                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $sales])
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
            var url = '{{ route("sales.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>
@stop
