@extends('admin.master')
@section('title', ' - Sms Api Setting')


@section('content')
    <main class="app-content">
        <div class="app-title">
            <div class="div">
                <h1><i class="fa fa-laptop"></i> Sms Api</h1>
                <p>Sms Api</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="#">Sms Api Setting</a></li>
            </ul>
        </div>


        <div class="tile mb-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header">
                        <h2 class="mb-3 line-head" id="navs">Sms Api List
                            <a href="{{ route('sms-apis.create') }}" role="button" class="btn btn-primary pull-right text-light">
                                <i class="fa fa-plus" aria-hidden="true"></i> Create
                            </a>
                        </h2>
                    </div>
                </div>
            </div>

            <div class="alert-message"></div>

            <div class="row" style="margin-bottom: 2rem;">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Srl.</th>
                                    <th>Title</th>
                                    <th>API Url</th>
                                    <th>Balance Url</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($smsApis as $key => $sms_api)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $sms_api->api_title }}</td>
                                        <td>{{ str_replace("&", " &", str_replace("?", " ?", $sms_api->api_url)) }}</td>
                                        <td>{{ str_replace("&", " &", str_replace("?", " ?", $sms_api->get_sms_balance_rul)) }}</td>
                                        <td>
                                            <a ></a>
                                            {!! $sms_api->status ? '<span class="btn btn-sm btn-success">Active</span>':'<span class="btn btn-sm btn-danger">Inactive</span>' !!}
                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-sm" href="{{ route('sms-apis.edit', $sms_api->id) }}"><span class="fa fa-edit"></span></a>
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
@endsection

@section('footer-script')

    <!-- fill edit form -->
    <script type="text/javascript">
        $('.create-panel').hide()
        $('.edit-panel').hide()

        $('.add-new-btn').click(function () {
            $('.edit-panel').hide()
            $('.create-panel').show()
        });
        $('.close-create-panel').click(function () {
            $('.create-panel').hide()
            $('.edit-panel').hide()
        });
        $('.create-api').click(function () {
            $('.create-from').submit()
        });
        $('.update-api').click(function () {
            $('.edit-form').submit()
        });

        $('.edit-api').click(function () {
            $('.create-panel').hide()

            let api_id = $(this).data('id')
            let root = $(this).closest('tr')
            let update_route = $('.edit-form').data('base_route') + "/" + api_id

            let edit_panel = $('.edit-panel')
            $('.edit-panel').find('.name').val(root.find('.name').text())
            $('.edit-panel').find('.username').val(root.find('.username').text())
            $('.edit-panel').find('.sender_number').val(root.find('.sender_number').text())
            $('.edit-panel').find('.url').text(root.find('.url').text())
            $('.edit-form').attr('action', update_route)

            edit_panel.show()
        })
    </script>
@endsection
