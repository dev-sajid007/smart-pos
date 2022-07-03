@extends('admin.master')
@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    @if(hasPermission('parties.create'))
                    <a href="{{ route('parties.create') }}" class="btn btn-primary" style="float: right;"><i class="fa fa-plus"></i> Add Party</a>
                    @endif
                    <h3><i class="fa fa-th-list"></i> Party Information</h3>

                    <div class="tile-body table-responsive">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                            <tr>
                                <th>Sl.</th>
                                <th width="10%">Party Code</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th width="12%" class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($parties as $key => $party)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $party->party_code }}</td>
                                    <td>{{ $party->name }}</td>
                                    <td>{{ $party->email }}</td>
                                    <td>{{ $party->phone }}</td>
                                    <td>{{ $party->address }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-corner">
                                            @include('admin.includes.user-log', ['data' => $party])

                                            @if(hasPermission('parties.edit'))
                                                <a class="btn btn-success btn-sm" title="Edit"
                                                   href="{{ route('parties.edit',$party->id) }}"> <i class="fa fa-edit"></i>
                                                </a>
                                            @endif
{{--                                            <a class="btn btn-primary btn-sm" title="View"  href="{{ route('parties.show',$party->id) }}"> <i class="fa fa-eye"></i> </a>--}}
                                            @if(hasPermission('parties.destroy'))
                                                <button type="button" class="btn btn-danger btn-sm" title="Delete" onclick="delete_item('{{ route('parties.destroy', $party->id) }}')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

{{--                        @include('admin.includes.pagination', ['data' => $parties])--}}
                    </div>
                </div>
            </div>
        </div>

        <!-- delete form -->
        @include('partials._delete_form')
    </main>


    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>

    <script>
        function formSubmit(id) {
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this data !",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel plz!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $('#deleteForm_' + id).submit();
                } else {
                    swal("Cancelled", "Your data is safe :)", "error");
                }
            });
        }
    </script>




@endsection
