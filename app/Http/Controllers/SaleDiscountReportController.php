<?php

namespace App\Http\Controllers;

use App\Sale;
use App\Supplier;
use Illuminate\Http\Request;

class SaleDiscountReportController extends Controller
{
    public function index()
    {
        $request = request();
        $suppliers = Supplier::companies()->pluck('name', 'id');
        $sale_discounts = Sale::companies()->with(['sale_details',
                        'sale_details.product' => function($product) use($request) {
                            $product->when($request->filled('fk_supplier_id'), function($produtSupplier) use($request) {
                                $produtSupplier->where('supplier_id', $request->fk_supplier_id);
                            });
                        }])
                        ->whereHas('sale_details.product', function($product) use($request){
                            $product->when($request->filled('fk_supplier_id'), function($produtSupplier) use($request) {
                                $produtSupplier->where('supplier_id', $request->fk_supplier_id);
                            });
                        })
                        ->whereBetween('sale_date', dateRange())
                        ->get();

        return view('admin.reports.supplier_wise_discount_reports', compact('suppliers', 'sale_discounts'));
    }
}
