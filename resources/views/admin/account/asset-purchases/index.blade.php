
@extends('admin.master')
@section('title', ' - Assets')

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    @if(hasPermission('accounts.create'))
                        <a href="{{ route('asset-purchases.create') }}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus"></i> Add New
                        </a>
                    @endif
                    <h3><i class="fa fa-th-list"></i> Asset Purchase List</h3>

                    <div class="tile-body" style="height: 740px !important;">

                        <!-- filter -->
                        <div class="row">
                            <div class="col-sm-8 mx-auto">
                                <form action="" method="get">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Search Asset</span>
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
                                        <th>SL</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th class="text-center">Amount</th>
                                        <th width="10%" class="text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($assetPurchases as $key => $asset)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $asset->date }}</td>
                                            <td>{{ $asset->description }}</td>
                                            <td class="text-right">{{ $asset->amount }}</td>
                                            <td class="text-center">
                                                <div class="btn-group btn-corner">
                                                    @if(hasPermission('accounts.show'))
                                                        <a class="btn btn-info btn-sm" title="View"
                                                           href="{{ route('asset-purchases.show', $asset->id) }}"> <i class="fa fa-eye"></i>
                                                        </a>
                                                    @endif

                                                    @if(hasPermission('accounts.destroy'))
                                                        <button type="button" class="btn btn-danger btn-sm" title="Delete" onclick="delete_item('{{ route('asset-purchases.destroy', $asset->id) }}')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="3"><strong>Total:</strong></th>
                                        <th class="text-right">{{ number_format($assetPurchases->sum('amount'), 2) }}</th>
                                        <th class="text-right"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- delete form -->
    @include('partials._delete_form')
@endsection

@section('footer-script')
    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
@endsection
