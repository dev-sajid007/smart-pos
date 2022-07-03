@extends('admin.master')
@section('title', ' - Developer Setting')

@section('css')
    <style type="text/css">
        .feature-name {
            color: #178e02db !important;
            font-size: 16px;
            font-weight: bold;
        }

        td, th {
            padding-left: 10px !important;
        }
    </style>
@endsection

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Setting</h1>
                <p>Developer Setting</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @include('partials._alert_message')

                <div class="tile">

                    <div class="tile-body pr-3 pl-3" style="min-height: 550px !important;">
                        <form class="form-horizontal" method="POST" action="{{ route('developer.settings.update') }}">
                            @csrf
                            <h3>Dynamic Features and Options</h3>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <th width="5%">Sl.</th>
                                            <th width="25%">Feature Name</th>
                                            <th>Configuration</th>
                                        </thead>

                                        <tbody>
                                            @foreach($optionData as $key => $item)
                                                @php $check = $settings->where('status', 1)->count(); @endphp

                                                <tr>
                                                    <td class="serial">{{ $key + 1 }}
                                                        <input type="hidden" name="titles[]" value="{{ $item['title'] }}">
                                                    </td>
                                                    <td class="feature-name">{{ $item['title'] }}</td>
                                                    <td>
                                                        <div class="animated-radio-button" id="status">
                                                            <label>
                                                                <input type="radio" name="statuses[{{ $key }}]" value="1" {{ $check > 0 ? 'checked' : '' }}><span class="label-text">Yes</span>
                                                            </label>
                                                            &nbsp;
                                                            &nbsp;
                                                            <label>
                                                                <input type="radio" name="statuses[{{ $key }}]" value="0" {{ $check < 1 ? 'checked' : '' }}><span class="label-text">No</span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-check"></i> Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('footer-script')
    <script>
        $('.module').click(function () {
            $(this).closest(".row").find('.permission').prop('checked', $(this).is(':checked'))
        })
        $('.check-all').click(function () {
            $(this).closest("form").find('.check').prop('checked', $(this).is(':checked'))
        })
    </script>
@endsection
