
@extends('admin.master')
@section('title', ' - Chart of Accounts')
@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    @if(hasPermission('accounts-charts.create'))
                        <a href="{{ route('accounts-charts.create') }}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Chart of Account</a>
                    @endif
                    <h3><i class="fa fa-th-list"></i> Chart of Accounts</h3>

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
                                    <th width="1%">#</th>
                                    <th>Chart Of Account Name</th>
                                    <th>Gl Account</th>
                                    <th>Head Type</th>
                                    <th width="10%" class="text-center">Status</th>
                                    <th width="10%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($account_chart as $key => $value)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $value->head_name }}</td>
                                        <td>{{ optional($value->gl_account)->name }}</td>
                                        <td>
                                            @if($value->head_type==0)
                                                Income
                                            @else
                                                Expanse
                                            @endif
                                        </td>
                                        <td width="10%" class="text-center">
                                            @if($value->status==1)
                                                <a href="{{route('charts.status', $value->id)}}"
                                                   class="btn btn-success btn-sm">Active</a>
                                            @else
                                                <a href="{{route('charts.status', $value->id)}}"
                                                   class="btn btn-warning btn-sm">Inactive</a>
                                            @endif
                                        </td>
                                        <td width="15%" class="text-center">
                                            <div class="btn-group btn-corner">
                                                @include('admin.includes.user-log', ['data' => $value])

                                                @if(hasPermission('accounts-charts.edit'))
                                                    <a class="btn btn-success btn-sm" title="Edit" href="{{ route('accounts-charts.edit',$value->id) }}"> <i class="fa fa-edit"></i> </a>
                                                @endif

                                                @if(hasPermission('accounts-charts.destroy'))
                                                    <button type="button" class="btn btn-danger btn-sm" title="Delete" onclick="delete_item('{{ route('accounts-charts.destroy', $value->id) }}')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $account_chart])
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- delete form -->
    @include('partials._delete_form')
@endsection

@section('footer-script')
@endsection
