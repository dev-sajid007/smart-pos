<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DailyQuotationController extends Controller
{
    public function index(){
        return view('admin.reports.daily_quotation');
    }
}
