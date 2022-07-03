
@extends('admin.master')
@section('title', ' - Courier')
@section('content')

    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <a href="{{ route('curriers.create') }}" class="btn btn-primary pull-right">
                        <i class="fa fa-plus"></i> Add New Courier
                    </a>
                    <h3><i class="fa fa-th-list"></i> Courier List</h3>

                    <div class="tile-body table-responsive">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Courier Name</th>
                                    <th>Courier Mobile</th>
                                    <th>Courier Address</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($curriers as $key => $currier)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $currier->name }}</td>
                                        <td>{{ $currier->mobile }}</td>
                                        <td>{{ $currier->address }}</td>
                                        <td>
                                            <a class="btn btn-info btn-sm" title="Edit"
                                               href="{{ route('curriers.edit', $currier->id) }}"> <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" title="Delete" onclick="delete_item('{{ route('curriers.destroy', $currier->id) }}')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @include('admin.includes.pagination', ['data' => $curriers])
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
