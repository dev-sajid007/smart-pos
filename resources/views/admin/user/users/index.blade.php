
@extends('admin.master')
@section('title', 'User List')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/custom_css/image_full_screen.css') }}">
@endsection

@section('content')

    <main class="app-content">
        <!-- The Modal -->
        @include('admin.includes.image_full_screen')

        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Manage User</h1>
                <p>Manage User Form</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                @include('partials._alert_message')

                <div class="tile">
                    <a href="{{ route('users.create') }}" class="btn btn-primary" style="float: right;">
                        <i class="fa fa-plus"></i> Add User
                    </a>
                    <h3 class="tile-title"><i class="fa fa-list"></i> User List</h3>

                    <!-- filter -->
                    @include('admin.user.users.search-filter')

                    <div class="tile-body" style="min-height: 560px !important;">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                                <tr>
                                    <th width="1%">#</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Email</th>
                                    <th class="text-center">Roll</th>
                                    <th class="text-center">Image</th>
                                    <th width="15%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td>{{ $key + $users->firstItem() }}</td>
                                        <td>{{ ucfirst($user->name) }}</td>
                                        <td>{{ optional($user->company)->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td class="text-center">{{ optional(optional($user->user_role)->role)->name }}</td>
                                        <td class="text-center">
                                            @if ($user->image)
                                                <div>
                                                    <img width="100" class="full-screen-image" style="cursor: pointer" title="Click on image to fullscreen" height="100" src="{{ asset($user->image) }}">
                                                </div>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group btn-corner">

                                                @if (auth()->id() != $user->id and $user->id != 1)
                                                    @if ($user->status)
                                                        <a class="btn btn-warning btn-sm text" title="Do Inactive" href="{{ route('users.status', $user->id) }}">
                                                            <i class="fa fa-thumbs-o-up"></i>
                                                        </a>
                                                    @else
                                                        <a class="btn btn-primary btn-sm " title="Do Active" href="{{ route('users.status', $user->id) }}">
                                                            <i class="fa fa-thumbs-o-down"></i>
                                                        </a>
                                                    @endif

                                                    <a class="btn btn-success btn-sm" title="Edit" href="{{ route('users.edit', $user->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <button type="button" data-toggle="modal" onclick="deleteItem(`{{ route('users.destroy', $user->id) }}`)" data-target="#DeleteModal" class="btn btn-danger btn-sm" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('admin.includes.delete_confirm')


@endsection

@section('footer-script')
    <script type="text/javascript" src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/custom_js/image_full_screen.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/custom_js/delete-item.js') }}"></script>
@endsection
