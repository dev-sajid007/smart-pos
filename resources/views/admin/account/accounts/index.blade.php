
@extends('admin.master')
@section('title', ' - Accounts')

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    @if(hasPermission('accounts.create'))
                        <a href="{{ route('accounts.create') }}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus"></i> Add Account
                        </a>
                    @endif
                    <h3><i class="fa fa-th-list"></i> Account List</h3>

                    <div class="tile-body" style="height: 740px !important;">

                        <!-- filter -->
                        <div class="row">
                            <div class="col-sm-8 mx-auto">
                                <form action="" method="get">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Search Account</span>
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
                                        <th>Account No</th>
                                        <th>Account Name</th>
                                        <th>Branch Name</th>
                                        <th class="text-right">Opening Balance</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Default</th>
                                        <th width="10%" class="text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($all_account as $key => $value)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value->account_no }}</td>
                                            <td>{{ $value->account_name }}</td>
                                            <td>{{ $value->branch_name }}</td>
                                            <td class="text-right">{{ $value->openingBalance }}</td>
                                            <td class="text-center">
                                                @if($value->status == 1)
                                                    <a href="{{ route('account.status', $value->id) }}"
                                                       class="btn btn-success btn-sm">Active</a>
                                                @else
                                                    <a href="{{ route('account.status', $value->id) }}"
                                                       class="btn btn-warning btn-sm">Inactive</a>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($value->default_account == 1)
                                                    <span class="badge badge-success" style="font-size: 15px">Yes</span>
                                                @else
                                                    <span class="badge badge-secondary" style="font-size: 15px">No</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-corner">
                                                    @if(hasPermission('accounts.edit'))
                                                        <a class="btn btn-info btn-sm" title="Edit"
                                                           href="{{ route('accounts.edit', $value->id) }}"> <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if($value->default_account == 1)
                                                        <button class="btn btn-danger btn-sm" disabled="" title="Can not Delete"><i class="fa fa-trash"></i></button>
                                                    @elseif(hasPermission('accounts.destroy'))
                                                        <button type="button" class="btn btn-danger btn-sm" title="Delete" onclick="delete_item('{{ route('accounts.destroy', $value->id) }}')">
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
        </div>
    </main>

    <!-- delete form -->
    @include('partials._delete_form')
@endsection

@section('footer-script')
    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
@endsection
