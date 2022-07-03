<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DailyPurchaseController extends Controller
{
    public function index(){
        return view('admin.reports.daily_purchase');
    }
}
