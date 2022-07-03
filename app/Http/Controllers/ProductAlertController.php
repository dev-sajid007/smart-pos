<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductStock;
use Auth;
use DB;

class ProductAlertController extends Controller
{
    public function index()
    {
        $product_stocks = ProductStock::with('stock_product')
            ->where('fk_company_id', companyId())
            ->whereHas('stock_product', function ($q){
                $q->whereColumn('product_alert_quantity', '>=', 'available_quantity');
            })
            ->get();


        return view('admin.reports.product_alert', [
            'product_stocks' => $product_stocks
        ]);
    }
}
