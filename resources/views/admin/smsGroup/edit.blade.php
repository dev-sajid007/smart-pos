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
                                            <td width="30%" class="no-print text-right">
                                                <div class="form-group" style="margin-top: 26px;">
                                                    <a href="{{ url('smsGroup') }}" class="btn btn-info text-white">
                                                        Group List <i class="fa fa-plus"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <form action="{{ route('smsGroup.update',$smsGroup->id) }}" method="POST">
                                                <td width="20%" class="no-print">
                                                    <label class="control-label">Group Name</label>
                                                    <input type="text" class="form-control" name="group_name"
                                                            value="{{ $smsGroup->group_name??old('group_name') }}"
                                                            autocomplete="off" placeholder="Ex. group 1" required>
                                                </td>
                                                <td width="30%" class="no-print">
                                                    <label class="control-label">Mobile Numbers</label>
                                                    <textarea name="group_mobiles" id="" cols="30"
                                                                rows="10" type="text" class="form-control"
                                                                placeholder="+8801777519553, +8801844047000" required
                                                    >{{ $smsGroup->group_mobiles??old('group_mobiles') }}</textarea>
                                                    <small id="" class="form-text text-muted">Add multiple mobile number coma(,) seperate.</small>
                                                </td>
                                                @csrf @method('put')
                                                <td width="10%" class="no-print">
                                                    <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                    <input class="form-control btn btn-success" type="submit" value="submit">
                                                </td>
                                            </form>
                                        </tr>
                                    </table>
                                </div>
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
        })
    </script>
    @include('admin.includes.date_field')
@endsection
