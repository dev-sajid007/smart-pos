@extends('admin.master')
@section('title', ' - Group SmS Lists')
@push('style')
    <style type="text/css">
        @media print {
            .d-none {
                display: block !important;
            }
            .d-print {
                display: block !important;
            }
        }
        .d-print {
            display: none;
        }
    </style>

@endpush
@php
    $count=0;
@endphp
@section('content')
    <main class="app-content">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <!-- Filter -->
                        <div class="row d-print-none filter-area">
                            <div class="col-md-11 mx-auto">
                                <div class="table-responsive">
                                    <input type="hidden" name="update_product_cost" value="">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            {{--<td width="20%">
                                                <label>Customer</label>
                                                <select name="marketers_id" class="form-control select2">
                                                    <option value="">All</option>
                                                        @foreach ($marketers as $key => $marketer)
                                                        <option value="{{ $marketer->id }}" {{ $marketer->id == request()->marketers_id ? 'selected':'' }}>
                                                            {{ $marketer->marketers_name??'' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>--}}
                                        <form action="{{ route('smsGroup.store') }}" method="POST">
                                            <td width="20%" class="no-print">
                                                <label class="control-label">Group Name</label>
                                                <input type="text" class="form-control" name="group_name"
                                                        value="{{ old('group_name')??'' }}"
                                                        autocomplete="off" placeholder="Ex. group 1" required>
                                            </td>
                                            <td width="30%" class="no-print">
                                                <label class="control-label">Mobile Numbers</label>
                                                <textarea name="group_mobiles" id="" cols="30"
                                                            rows="10" type="text" class="form-control"
                                                            placeholder="+8801777519553, +8801844047000" required>{{ old('group_mobiles')  }}</textarea>
                                                <small id="" class="form-text text-muted">Add multiple mobile number coma(,) seperate.</small>
                                            </td>
                                            @csrf
                                            <td width="10%" class="no-print">
                                                <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                <input class="form-control btn btn-success" type="submit" value="submit">
                                            </td>
                                        </form>
                                            <td width="30%" class="no-print text-right">
                                                <div class="form-group" style="margin-top: 26px;">
                                                    {{-- <button class="btn btn-primary"><i class="fa fa-check"></i> Check </button> --}}
                                                    {{-- <a href="{{ url('smsGroup/create') }}" class="btn btn-info text-white">
                                                        Add New <i class="fa fa-plus"></i></a> --}}
                                                    <button type="button" class="btn btn-success" onclick="window.print()">
                                                        <i class="fa fa-print"></i> Print
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Sale List -->
                        <div class="row">
                            <div class="col-sm-11 mx-auto">
                                <table class="table table-bordered table-sm" id="stock_table" style="border: none !important;">
                                    <thead>
                                        <tr>
                                            <td colspan="9" class="text-center py-2" style="border: none !important;">
                                                @include('partials._print_header')
                                                <h4>Group SmS Lists</h4>
                                                @if (request('start_date'))
                                                    <p>
                                                        Showing Sales From <b>{{ request('start_date') }}</b>
                                                        to <b>{{ request('end_date') }}</b>
                                                    </p>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left" width="5%">Sl.</th>
                                            <th class="text-left" >Group Name</th>
                                            <th class="text-center">Numbers Count</th>
                                            <th class="text-right" width="15%">Created Date</th>
                                            <th class="text-right" width="15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($smsGroups as $key => $smsGroup)
                                            <tr>
                                                <td class="text-left">{{ $key + 1}}</td>
                                                <td class="text-left">{{ $smsGroup->group_name??'' }}</td>
                                                @php
                                                    $group_mobiles = $smsGroup->group_mobiles;
                                                    $explore_array = explode(",", $group_mobiles);
                                                    $count_mobiles = count($explore_array);
                                                @endphp
                                                <td class="text-center">{{ $count_mobiles??'' }}</td>
                                                <td class="text-right">{{ $smsGroup->created_at??'' }}</td>
                                                <td class="text-right">
                                                    <a href="{{ route('smsGroup.edit',$smsGroup->id) }}" class="btn btn-info btn-sm p-1" style="border-radius: 100px;">
                                                        <i class="text-white fa fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-sm p-1" title="Delete" style="border-radius: 100px;"
                                                            onclick="delete_item('{{ route('smsGroup.destroy', $smsGroup->id) }}')"
                                                            type="button">
                                                        <i class="text-white fa fa-trash"></i>
                                                    </button>
                                                </td>
                                        @endforeach
                                    <form action="" id="deleteItemForm" method="POST">
                                        @csrf @method("DELETE")
                                    </form>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap.min.js')}}"></script>

    <script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>


    <script type="text/javascript">

        $('.filter-area').hide()
        $('.add-product-cost-area').hide()

        function addProductCost(status)
        {
            if (status == 1) {
                $('.filter-area').hide()
                $('.add-product-cost-area').show()
            } else {
                $('.filter-area').show()
                $('.add-product-cost-area').hide()
            }
        }

        $(document).ready(function () {
            if($('.is-add-product-cost').val() == 'update') {
                addProductCost(1)
            } else {
                addProductCost(0)
            }
        });


    function delete_item(url)
    {
        Swal.fire({
            title: 'Are you sure ?',
            html: "<b>You want to delete permanently !</b>",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            width:400,
        }).then((result) =>{
        if(result.value){
            $('#deleteItemForm').attr('action', url).submit();
            }
        })
    }
    </script>
    @include('admin.includes.date_field')
@endsection
