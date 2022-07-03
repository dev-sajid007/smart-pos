
@extends('admin.master')
@section('title', 'Marketers List')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/custom_css/image_full_screen.css') }}">
    <style>
        th {
            background: #156984 !important;
            height: 25px !important;
            vertical-align: middle !important;
            font-size: 16px !important;
            color: white;
        }
        td {
            font-size: 13px !important;
        }
    </style>
@endsection


@section('content')

    <main class="app-content">
        <!-- The Modal -->
        @include('admin.includes.image_full_screen')

        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <a href="{{ route('marketers.create') }}" class="btn btn-primary" style="float: right;"><i class="fa fa-plus"></i> Add New</a>
                    <h3 class="tile-title"><i class="fa fa-list"></i> Marketers List </h3>
                    <!-- filter -->
                    <div class="row">
                        <div class="col-sm-8 mx-auto">
                            <form action="" method="get">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="height: 33px !important;" >Search marketer</span>
                                    </div>
                                    <input class="form-control form-control-md"name="search" placeholder="Search anything.........." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button style="cursor: pointer" type="submit" class="input-group-text"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="tile-body table-responsive" style="font-size: 12px;">
                        <table class="table table-hover table-bordered table-sm" id="sampleTable">
                            <thead>
                                <tr>
                                    <th class="text-center">Sl.</th>
                                    <th>Marketer Name</th>
                                    <th>Phone</th>
                                    <th class="text-center">Amount Given</th>
                                    <th class="text-center">Balance</th>
                                    {{-- <th class="text-center">Images</th> --}}
                                    <th width="13%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($marketers as $key => $marketer)
                                    <tr>
                                        <td class="text-center">{{ $key + $marketers->firstItem() }}</td>
                                        <td>{{ $marketer->marketers_name }}</td>
                                        <td>{{ $marketer->marketers_mobile }}</td>
                                        <td class="text-center">
                                            {{ $marketer->balance??0 }}
                                            {{-- <a class="btn btn-info btn-sm text-white float-right"
                                               href="{{ route('marketers.ledger.edit', $marketer->id) }}">
                                                <i class="fa fa-edit"></i>
                                            </a> --}}
                                        </td>
                                        <td class="text-center">{{ $marketer->balance??0 }}</td>
                                        {{--
                                            <td class="text-center">
                                                @if ($marketer->image)
                                                    <div>
                                                        <img width="100" class="full-screen-image"
                                                            style="cursor: pointer" title="Click on image to fullscreen"
                                                            height="100" src="{{ asset($marketer->image) }}">
                                                    </div>
                                                @endif
                                            </td>--}}
                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                <a class="btn btn-success btn-sm"  title="Send Money"
                                                    href="{{ route('marketers.ledger.create', $marketer->id) }}">
                                                    <i class="fa fa-usd" aria-hidden="true"></i>
                                                </a>
                                                <a class="btn btn-primary btn-sm" title="Edit"
                                                    href="{{ route('marketers.edit', $marketer->id) }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a title="Delete marketer" href="#" class="btn btn-danger btn-sm"
                                                    data-toggle="modal" onclick="deleteData({{ $marketer->id }})"
                                                    data-target="#DeleteModal"><i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @include('admin.includes.delete_confirm')
                            </tbody>
                        </table>
                        @include('admin.includes.pagination', ['data' => $marketers])
                    </div>
                </div>

            </div>
        </div>
    </main>


@endsection
@section('js')
    <!-- Data table plugin-->

    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/sweetalert.min.js')}}"></script>

    <script type="text/javascript">
        function deleteData(id) {
            var id = id;
            var url = '{{ route("marketers.destroy", ":id") }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>

    <script type="text/javascript" src="{{ asset('assets/custom_js/image_full_screen.js') }}"></script>
@endsection
