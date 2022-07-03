<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Auth;

class HomeController extends Controller
{
    private $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(DashboardService  $dashboardService)
    {
        $this->middleware('auth');
        $this->service = $dashboardService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {

//        if(strpos(url('/'), '127.0.0.1') !== false || strpos(url('/'), 'localhost') !== false) {
//            return view('dashboard', [
//                'accounts'              => collect([]),
//                'yesterdaySaleAmount'   => 0,
//                'yesterdayPurchaseAmount' => 0,
//                'todaySaleAmount'       => 0,
//                'todayPurchaseAmount'   => 0,
//                'monthlySaleAmount'     => 0,
//                'monthlyPurchaseAmount' => 0,
//                'todayVoucher'          => collect([]),
//                'monthlyVoucher'        => collect([]),
//                'payable'               => 0,
//                'receivable'            => 0
//            ]);
//        }


        return view('dashboard', [

            // required data
            'accounts'                  => $this->service->getAccountBalances(),
            'yesterdaySaleAmount'       => $this->service->yesterdaySaleTotal(),
            'yesterdayPurchaseAmount'   => $this->service->yesterdayPurchaseTotal(),
            'todaySaleAmount'           => $this->service->todaySaleTotal(),
            'todayPurchaseAmount'       => $this->service->getTodayTotalPurchaseAmount(),
            'monthlySaleAmount'         => $this->service->monthlySaleTotal(),
            'monthlyPurchaseAmount'     => $this->service->getTotalMonthlyPurchaseAmount(),
            'todayVoucher'              => $this->service->getTodayVoucherAmount(),
            'monthlyVoucher'            => $this->service->getMonthlyVoucherAmount(),
            'payable'                   => $this->service->getTotalPayable(),
            'receivable'                => $this->service->getTotalReceivable(),
            'sales_graph'               => $this->service->getOneWeekSalesAmount(),
            'purchase_graph'            => $this->service->getOneWeekPurchaseAmount(),
            'week_days'                 => $this->service->getWeekDays()
        ]);
    }

}
