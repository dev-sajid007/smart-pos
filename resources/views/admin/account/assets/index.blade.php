
@extends('admin.master')
@section('title', ' - Assets')

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    @if(hasPermission('accounts.create'))
                        <a href="{{ route('assets.create') }}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus"></i> Add Asset
                        </a>
                    @endif
                    <h3><i class="fa fa-th-list"></i> Asset List</h3>

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
                                        <th>Asset Name</th>
                                        <th>Description</th>
                                        <th class="text-center">Opening</th>
                                        <th class="text-center">Current Balance</th>
                                        <th width="10%" class="text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($assets as $key => $asset)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $asset->name }}</td>
                                            <td>{{ $asset->description }}</td>
                                            <td class="text-right">{{ $asset->opening }}</td>
                                            <td class="text-right">{{ $asset->current_balance }}</td>
                                            <td class="text-center">
                                                <div class="btn-group btn-corner">
                                                    @if(hasPermission('accounts.edit'))
                                                        <a class="btn btn-info btn-sm" title="Edit"
                                                           href="{{ route('assets.edit', $asset->id) }}"> <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if($asset->default_account == 1)
                                                        <button class="btn btn-danger btn-sm" disabled="" title="Can not Delete"><i class="fa fa-trash"></i></button>
                                                    @elseif(hasPermission('accounts.destroy'))
                                                        <button type="button" class="btn btn-danger btn-sm" title="Delete" onclick="delete_item('{{ route('assets.destroy', $asset->id) }}')">
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
                                        <th class="text-right">{{ number_format($assets->sum('opening'), 2) }}</th>
                                        <th class="text-right">{{ number_format($assets->sum('current_balance'), 2) }}</th>
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
