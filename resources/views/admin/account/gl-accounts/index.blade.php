
@extends('admin.master')
@section('title', ' - Gl Accounts')

@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
{{--                    @if(hasPermission('gl-accounts.create'))--}}
{{--                        <a href="{{ route('gl-accounts.create') }}" class="btn btn-primary pull-right">--}}
{{--                            <i class="fa fa-plus"></i> Add Account--}}
{{--                        </a>--}}
{{--                    @endif--}}
                    <h3><i class="fa fa-th-list"></i> Gl Account List</h3>

                    <!-- filter -->
{{--                    <div class="row">--}}
{{--                        <div class="col-sm-8 mx-auto">--}}
{{--                            <form action="" method="get">--}}
{{--                                <div class="input-group">--}}
{{--                                    <div class="input-group-prepend">--}}
{{--                                        <span class="input-group-text">Search Account</span>--}}
{{--                                    </div>--}}
{{--                                    <input class="form-control form-control-md" name="search" placeholder="Search anything.........." value="{{ request('search') }}">--}}
{{--                                    <div class="input-group-append">--}}
{{--                                        <button style="cursor: pointer" type="submit" class="input-group-text btn-sm"><i class="fa fa-search"></i></button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <div class="tile-body table-responsive">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Account Name</th>
                                    <th width="10%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($accounts as $key => $value)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $value->name }}</td>
                                        <td class="text-center">
                                            @if(hasPermission('accounts.edit'))
                                                <a class="btn btn-info btn-sm" title="Edit"
                                                   href="{{ route('gl-accounts.edit', $value->id) }}"> <i class="fa fa-edit"></i>
                                                </a>
                                            @endif

{{--                                            @if($value->default_account==1)--}}
{{--                                                <button class="btn btn-danger btn-sm" disabled="" title="Can not Delete"><i class="fa fa-trash"></i></button>--}}
{{--                                            @elseif(hasPermission('accounts.destroy'))--}}
{{--                                                <button type="button" class="btn btn-danger btn-sm" title="Delete" onclick="delete_item('{{ route('gl-accounts.destroy', $value->id) }}')">--}}
{{--                                                    <i class="fa fa-trash"></i>--}}
{{--                                                </button>--}}
{{--                                            @endif--}}
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

    <!-- delete form -->
    @include('partials._delete_form')
@endsection

@section('footer-script')
    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
@endsection
