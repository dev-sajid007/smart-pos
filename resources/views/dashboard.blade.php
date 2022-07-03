@extends('admin.master')
@section('title', ' - Dashboard')

@push('style')
    <style>
        th.dash-head {
               background-color: #ECF0F1;
               color: #000;
			   padding: 1px;
			  font-family: -webkit-pictograph;
        }
    </style>
@endpush

@section('content')
    <main class="app-content">
        <div class="row">

            @if(hasPermission('today.sale.purchase'))
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small primary coloured-icon">
                        <i class="icon fa fa-shopping-cart fa-2x"></i>
                        <div class="info" style="padding-top: 5px !important; padding-left: 5px !important;">
                            <h4 style="margin-top: 0 !important; padding-top: 0!important;">Yesterday</h4>
                            <p style="padding-top: 0 !important; font-size: 13px !important;"><b>Sale: {{ number_format($yesterdaySaleAmount, 2) }} TK</b></p>
                            <p style="padding-top: 0 !important; margin-top: -5px !important; font-size: 13px !important;"><b>Purchase: {{ number_format($yesterdayPurchaseAmount, 2) }} TK</b></p>
                        </div>
                    </div>
                </div>
            @endif

            @if(hasPermission('today.sale.purchase'))
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small primary coloured-icon">
                        <i class="icon fa fa-shopping-cart fa-2x" style="background: #90a229"></i>
                        <div class="info" style="padding-top: 5px !important; padding-left: 5px !important;">
                            <h4 style="margin-top: 0 !important; padding-top: 0!important;">Today</h4>
                            <p style="padding-top: 0 !important; font-size: 13px !important;"><b>Sale: {{ number_format($todaySaleAmount, 2) }} TK</b></p>
                            <p style="padding-top: 0 !important; margin-top: -5px !important; font-size: 13px !important;"><b>Purchase: {{ number_format($todayPurchaseAmount, 2) }} TK</b></p>
                        </div>
                    </div>
                </div>
            @endif

            @if(hasPermission('today.income.expense'))
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small info coloured-icon">
                        <i class="icon fa fa-shopping-cart fa-2x"></i>
                        <div class="info" style="padding-top: 5px !important; padding-left: 5px !important;">
                            <h4 style="margin-top: 0 !important; padding-top: 0!important;">Today</h4>
                            <p style="padding-top: 0 !important; font-size: 13px !important;"><b>Income: {{ number_format($todayVoucher->where('amount', '>', 0)->sum('amount'), 2) }} TK</b></p>
                            <p style="padding-top: 0 !important; margin-top: -5px !important; font-size: 13px !important;"><b>Expense:{{ number_format($todayVoucher->where('amount', '<', 0)->sum('amount') * (-1), 2) }} TK</b></p>
                        </div>
                    </div>
                </div>
            @endif


            @if(hasPermission('monthly.sale.purchase'))
                <div class="col-md-6 col-lg-3">
                    <div class="widget-small primary coloured-icon">
                        <i class="icon fa fa-shopping-cart fa-2x" style="background: #5ca9a9"></i>
                        <div class="info" style="padding-top: 5px !important; padding-left: 5px !important;">
                            <h4 style="margin-top: 0 !important; padding-top: 0!important;">Monthly</h4>
                            <p style="padding-top: 0 !important; font-size: 13px !important;"><b>Sale: {{ number_format($monthlySaleAmount, 2) }} TK</b></p>
                            <p style="padding-top: 0 !important; margin-top: -5px !important; font-size: 13px !important;"><b>Purchase: {{ number_format($monthlyPurchaseAmount, 2) }} TK</b></p>
                        </div>
                    </div>
                </div>
            @endif

            @if(hasPermission('monthly.income.expense'))
            <div class="col-md-6 col-lg-3">
                <div class="widget-small info coloured-icon">
                    <i class="icon fa fa-shopping-cart fa-2x" style="background: #82609c"></i>
                    <div class="info" style="padding-top: 5px !important; padding-left: 5px !important;">
                        <h4 style="margin-top: 0 !important; padding-top: 0!important;">Monthly</h4>
                        <p style="padding-top: 0 !important; font-size: 13px !important;"><b>Income: {{ number_format($monthlyVoucher->where('amount', '>', 0)->sum('amount'), 2) }} TK</b></p>
                        <p style="padding-top: 0 !important; margin-top: -5px !important; font-size: 13px !important;"><b>Expense: {{ number_format($monthlyVoucher->where('amount', '<', 0)->sum('amount') * (-1), 2) }} TK</b></p>
                    </div>
                </div>
            </div>
            @endif

            @if(hasPermission('accounts.liabilities'))
            <div class="col-md-6 col-lg-3">
                <div class="widget-small danger coloured-icon">
                    <i class="icon fa fa-briefcase fa-2x"></i>
                    <div class="info" style="padding-top: 5px !important; padding-left: 5px !important;">
                        <h4 style="margin-top: 0 !important; padding-top: 0!important;">Liabilities</h4>
                        <p style="padding-top: 0 !important; font-size: 13px !important;"><b>Payable Due: {{ number_format($payable, 2) }} TK</b></p>
                        <p style="padding-top: 0 !important; margin-top: -5px !important; font-size: 13px !important;"><b>Receivable Due: {{ number_format($receivable, 2) }} TK</b></p>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="row ">
            <!-- Available Amount -->
            @if(hasPermission('available.amount'))
                <div class="col-sm-12 px-4">
                    <div class="tile ">
                        <table class="table table-bordered table-sm text-center">
                            <tr>
                                <th colspan="2" class="dash-head">
                                    <h4 class="pull-left">Available Amount</h4>
                                    <h4 class="pull-right">{{ number_format($accounts->sum('total_amount'), 2) }} TK</h4>
                                </th>
                            </tr>

                            @foreach($accounts as $account)
                                <tr>
                                    <td width="50%" class="text-left">
                                        <b>{{ $account->account_name }}</b>
                                    </td>
                                    <td width="50%" class="text-right">
                                        <b>{{ number_format($account->total_amount, 2) }}</b> &nbsp;TK
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            @endif

            <!-- Monthly Account Chart -->
            @if(hasPermission('monthly.account.chart'))
                <div class="col-sm-12 px-4">
                    <div class="tile" style="max-height: 550px">
                        <h3 class="tile-title">Monthly Account Chart</h3>
                        <div class="tile-body">
                            <div class="embed-responsive embed-responsive-16by9" style="height: 254px">
                                <canvas class="embed-responsive-item" id="lineChartDemo" style="height: 250px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </main>

    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
{{--    <script src="{{ asset('assets/admin/js/plugins/pace.min.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/chart.js') }}"></script>
    <script type="text/javascript">
        var days = [<?php echo '"'.implode('","', $week_days).'"' ?>];
        var sales = [<?php echo '"'.implode('","', $sales_graph).'"' ?>];
        var purchases = [<?php echo '"'.implode('","', $purchase_graph).'"' ?>];

        var data = {
            labels: days,
            datasets: [
                {
                    label: "My First dataset",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: purchases
                },
                {
                    label: "My Second dataset",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: sales
                }
            ]
        };
        var pdata = [
            {
                value: 300,
                color:"#F7464A",
                highlight: "#FF5A5E",
                label: "Red"
            },
            {
                value: 50,
                color: "#46BFBD",
                highlight: "#5AD3D1",
                label: "Green"
            },
            {
                value: 100,
                color: "#FDB45C",
                highlight: "#FFC870",
                label: "Yellow"
            }
        ]

        var ctxl = $("#lineChartDemo").get(0).getContext("2d");
        var lineChart = new Chart(ctxl).Line(data);

    </script>
{{--    <!-- Google analytics script-->--}}
{{--    <script type="text/javascript">--}}
{{--        if(document.location.hostname == 'pratikborsadiya.in') {--}}
{{--            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){--}}
{{--                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),--}}
{{--                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)--}}
{{--            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');--}}
{{--            ga('create', 'UA-72504830-1', 'auto');--}}
{{--            ga('send', 'pageview');--}}
{{--        }--}}
{{--    </script>--}}
@endsection
