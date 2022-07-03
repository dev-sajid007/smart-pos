
@extends('admin.master')
@section('title', ' - Wastage Report')

@push('style')
    <style type="text/css">
        @media print {
            .d-none {
                display: block !important;
            }
            .d-print {
                display: block !important;
            }
        }
        .d-print {
            display: none;
        }

    </style>
@endpush

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <!-- Filter -->
                        <div class="row d-print-none">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <form method="get">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="15%">
                                                    <label>Customer</label>
                                                    <select name="fk_customer_id" class="form-control select2">
                                                        <option value="">Select</option>
                                                        @foreach ($customers as $id => $name)
                                                            <option value="{{ $id }}" {{ $id == request()->fk_customer_id ? 'selected':'' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td width="15%">
                                                    <label>Supplier</label>
                                                    <select name="fk_supplier_id" class="form-control select2">
                                                        <option value="">Select</option>
                                                        @foreach ($suppliers as $id => $name)
                                                            <option value="{{ $id }}" {{ $id == request()->fk_supplier_id ? 'selected':'' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td width="20%" class="no-print">
                                                    <label class="control-label">Start Date</label>
                                                    <input type="text" class="form-control dateField" name="from" value="{{ request()->get('from') ?? date('Y-m-d') }}" autocomplete="off">
                                                </td>

                                                <td width="20%" class="no-print">
                                                    <label class="control-label">End Date</label>
                                                    <input type="text" class="form-control dateField" name="to" value="{{ request()->get('to') ?? date('Y-m-d') }}" autocomplete="off">
                                                </td>


                                                <td width="20%" class="no-print">

                                                    <div class="form-group" style="margin-top: 26px;">
                                                        <button class="btn btn-primary">
                                                            <i class="fa fa-check"></i> Check
                                                        </button>
                                                        <a href="{{ url('reports/wastages') }}" class="btn btn-danger"><i class="fa fa-refresh"></i></a>
                                                        <button type="button" class="btn btn-success" onclick="window.print()">
                                                            <i class="fa fa-print"></i> Print
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- display data to table -->
                        <div class="tile-body table-responsive table-sm d-print-none">
                            <table class="table table-hover table-bordered" id="sampleTable" style="border: none !important;">
                                <thead>
                                    <tr>
                                        <td colspan="9" class="text-center py-2" style="border: none !important;">
                                            <h4>Wastage / Damage Report</h4>
                                            @if (request('from'))
                                                <p>
                                                    Showing Wastage / Damage From <b>{{request('from')}}</b>
                                                    to <b>{{request('to')}}</b>
                                                </p>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="1%">Sl.</th>
                                        <th>Date</th>
                                        <th>Product Name</th>
                                        <th>Reference</th>
                                        <th>Damaged From</th>
                                        <th>Description</th>
                                        <th style="text-align: center">Quantity</th>
                                        <th style="text-align: right; padding-right: 10px">Price</th>
                                    </tr>
                                </thead>

                                <tbody class="d-print-none">
                                    @foreach ($wastages as $key => $wastage)
                                        <tr>
                                            <td>{{ $key + $wastages->firstItem() }}</td>
                                            <td>{{ $wastage->created_at->format('Y-m-d') }}</td>
                                            <td>{{ $wastage->itemable->product->product_name}}</td>
                                            <td>{{ $wastage->itemable->reference ?? '' }}</td>
                                            <td>{{ $wastage->typeName()}}</td>
                                            <td>{{ $wastage->itemable->description ?? ''}}</td>
                                            <td class="quantity" style="text-align: center">{{ $wastage->quantity}}</td>
                                            <td class="price" style="text-align: right; padding-right: 10px">{{ number_format($wastage->itemable->amount ?? 0, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @include('admin.includes.pagination', ['data' => $wastages])
                        </div>

                        <!-- display area for print -->
                        <div class="tile-body table-sm d-print">
                            <table class="table table-hover table-bordered" style="border: none !important;">
                                <thead>
                                    <tr>
                                        <td colspan="9" class="text-center py-2" style="border: none !important;">
                                            @include('partials._print_header')
                                            <h4>Wastage Report</h4>
                                            @if (request('from'))
                                                <p>
                                                    Showing wastages From <b>{{request('from')}}</b>
                                                    to <b>{{request('to')}}</b>
                                                </p>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="1%">Sl.</th>
                                        <th>Date</th>
                                        <th>Product Name</th>
                                        <th>Reference</th>
                                        <th>Damaged From</th>
                                        <th>Description</th>
                                        <th style="text-align: center">Quantity</th>
                                        <th style="text-align: right; padding-right: 10px">Price</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($wastagesFull as $wastage)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $wastage->created_at->format('Y-m-d') }}</td>
                                            <td>{{ $wastage->itemable->product->product_name}}</td>
                                            <td>{{ $wastage->itemable->reference ?? '' }}</td>
                                            <td>{{ $wastage->typeName()}}</td>
                                            <td>{{ $wastage->itemable->description ?? ''}}</td>
                                            <td class="quantity" style="text-align: center">{{ $wastage->quantity}}</td>
                                            <td class="price" style="text-align: right; padding-right: 10px">{{ number_format($wastage->itemable->amount ?? 0, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap.min.js')}}"></script>

    <script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>



    <script>

//total quantity
    var total_quantity = 0;
    $('.quantity').each(function(){
        var quantity = $(this).text() != NaN ? $(this).text():0;
        total_quantity += parseFloat(quantity);
    });
    $('#total_quantity').val(total_quantity);

//total price
    var total_price = 0;
    $('.price').each(function(){
        var price = $(this).text() != NaN ? $(this).text():0;
        total_price += parseFloat(price);
    });
    $('#total_price').val(total_price);


    </script>

    @include('admin.includes.date_field')

@endsection
