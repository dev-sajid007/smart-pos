<!DOCTYPE html>

<html lang="en">
    <head class="d-print-none">
        <meta name="description" content="">

        <title> Smart Account - @yield('title', 'Best Accounting Software In Bangladesh')</title>
        <meta name="title" content="Smart Account - Best Accounting Software In Bangladesh"/>
        <meta property="og:title" content="Best Accounting Software In Bangladesh - Smart Software Ltd">
        <meta property="og:image" content="/assets/logo.png">
        <meta property="og:image:secure_url" content="/assets/logo.png">
        <meta property="og:image:alt" content="Smart Account - Best Accounting Software In Bangladesh">
        <meta property="og:image:type" content="image/png">
        <meta name="twitter:title" content="Smart Account - Best Accounting Software In Bangladesh">
        <meta name="Googlebot" content="all"/>
        <meta http-equiv="imagetoolbar" content="yes"/>
        <meta name="Author" content="Smart Software Ltd."/>
        <meta name="Rating" content="General"/>
        <meta http-equiv="Pragma" content="no-cache"/>
        <meta name="distribution" content="Global"/>
        <meta property="article:publisher" content="https://www.facebook.com/smartsoftbd">
        <meta name="twitter:card" content="summary_large_image">

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!--  favicon -->
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/favicons/android-icon-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicons/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('assets/favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('assets/favicons/ms-icon-144x144.png') }}">
        <meta name="theme-color" content="#ffffff">


        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
        <!-- Font-icon css-->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.min.css') }}">

        @stack('style')
        <style>
            .datepicker {
                z-index: 99999 !important;
            }

            li.treeview {
                border: 0.1px solid #e2e2e2;
            }
        </style>


        @yield('css')
    </head>

@php
    $hasMultipleCompany = \App\Company::count() > 1 ? true : false;
    $settings       = \App\SoftwareSetting::companies()->get();

    $tests = App\SoftwarePayment::where('status', 0)
             ->where('software_payment_date', '<', date('Y-m-d'))
             ->orderBy('software_payment_date','ASC')->first();
@endphp
    <body class="app sidebar-mini rtl" translate="no">
        @include('admin.includes.header')

        <!-- alert Start -->
        <div class="d-print-none app-title bg-danger text-white" id="alert_module"
            style="width: auto;
                display:none;
                margin-top: 50px;
                text-align: center;
                padding: 0px !important;
                margin-bottom: 0px !important;">
            @include('admin.includes.alert')
            @if (\Session::has('error'))
                <div class="alert alert-danger">
                    <ul>
                        <li>{!! \Session::get('error') !!}</li>
                    </ul>
                </div>
            @elseif (\Session::has('success'))
                <div class="alert alert-success">
                    <ul>
                        <li>{!! \Session::get('success') !!}</li>
                    </ul>
                </div>
            @else
            @endif
        </div>
        <!-- Sidebar menu-->
        @include('admin.includes.sidebar')


        @yield('content')

        <!-- Essential javaScript for application to work-->
        <script src="{{ asset('js/app.js') }}"></script>

        <script type="text/javascript" src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>

        <!-- confirm delete dialog box -->
        <script type="text/javascript" src="{{ asset('assets/admin/jq/delete_confirm.js') }}"></script>

        @include('sweetalert::alert')


        <!-- Number format -->
        <script type="text/javascript">
            function amountFormat(amount) {
                return (amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
            }

            $(function () {
                $('[data-toggle="popover"]').popover({html: true, container: 'body'})
            })


            function onlyNumber(evnt) {
                if (evnt.charCode == 13) {
                    evnt.preventDefault();
                }

                var str = evnt.target.value
                var n = str.includes(".")
                if (event.charCode == 46 && n) {
                    return false
                }

                return (evnt.charCode >= 46 && evnt.charCode <= 57) || event.charCode == 13
            }

            $('.only-number').keypress(function() {
                if (event.charCode == 13) {
                    event.preventDefault();
                }
                var str = $(this).val()
                var n = str.includes(".")
                if (event.charCode == 46 && n) {
                    return false
                }

                return (event.charCode >= 46 && event.charCode <= 57) || event.charCode == 13
            })
            window.setTimeout(function () {
                $(".alert-danger, .alert-success").fadeTo(1000, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 3000);

            $('#sampleTable').DataTable({
                "bPaginate": false,
                "searching": false,
                order: []
            });


            $('.dateField').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true
            });


            $('.select2').select2();
        </script>

        @include('admin.includes.keyboard-shortcut')

        <script type="text/javascript">
            const fullUrl = "{{ request()->fullUrl() }}"
            let treeViewItem = document.querySelector('a.treeview-item.active')
            if (treeViewItem && treeViewItem.href === fullUrl) {
                treeViewItem.parentElement.parentElement.parentElement.classList.add('is-expanded')
            }
        </script>

        @yield('js')

        @section('footer-script')
        @show
    </body>
</html>
