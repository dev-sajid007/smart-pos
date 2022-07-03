
@extends('admin.master')

@section('title', ' - Stock Transfer')

@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div>
                        {{--                        @if(hasPermission('sales.create'))--}}
                        <a href="{{ route('warehouse-to-warehouses.create') }}" class="btn btn-primary btn-sm pull-right">
                            <i class="fa fa-plus"></i> Add Transfer
                        </a>
                        {{--                        @endif--}}
                        <h3><i class="fa fa-th-list"></i> Warehouse To Warehouse Stock Transfer</h3>

                        <!-- filter -->
                        <div class="row">
                            <div class="col-sm-8 mx-auto">
                                <form action="" method="get">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Search Transfer</span>
                                        </div>
                                        <input class="form-control form-control-md" name="search" placeholder="Search anything.........." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button style="cursor: pointer" type="submit" class="input-group-text btn-sm"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>



                    <div class="tile-body table-responsive table-sm">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th width="1%">Sl.</th>
                                <th width="8%">Date</th>
                                <th width="10%">Invoice ID</th>
                                <th>From Warehouse</th>
                                <th>To Warehouse</th>
                                <th>Total Qty</th>
                                <th>Created By</th>
                                <th width="12%" class="text-center">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($stocks as $key => $stock)
                                <tr>
                                    <td>{{ $key + $stocks->firstItem() }}</td>
                                    <td>{{ $stock->date }}</td>
                                    <td>{{ $stock->invoice_no }}</td>
                                    <td>{{ optional($stock->from_warehouse)->name ?? 'Show Room' }}</td>
                                    <td>{{ optional($stock->to_warehouse)->name ?? 'Show Room' }}</td>
                                    <td>{{ $stock->total_quantity }}</td>
                                    <td>{{ ucwords(optional($stock->created_user)->name) ?? 'System Admin' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-corner">
                                            <a class="btn btn-sm btn-primary" title="Normal Print" href="{{ route('warehouse-to-warehouses.show', $stock->id) }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" title="Delete" onclick="delete_item('{{ route('warehouse-to-warehouses.destroy', $stock->id) }}')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $stocks])
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- delete form -->
    @include('partials._delete_form')
@endsection
