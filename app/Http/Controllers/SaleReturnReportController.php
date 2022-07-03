<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\SaleReturn;
use App\Supplier;

class SaleReturnReportController extends Controller
{
    public function report()
    {
        $request = request();
        $suppliers = Supplier::companies()->pluck('name', 'id');
        $sale_returns = $request->filled('start_date') ? $this->getReport()->paginate(50) : collect([]);

        return view('admin.reports.sale_return_report', compact('suppliers', 'sale_returns'));
    }


    public function printReport()
    {
        $sale_returns = $this->getReport()->get();
        return view('admin.reports.print_sale_return_report', compact('sale_returns'));
    }


    private function getReport()
    {
        $request = request();

        $sale_returns = SaleReturn::companies()
            ->with('sale.sale_details', 'sale.sale_details.product')
            ->when($request->filled('start_date'), function($q) use($request){
                $q->whereBetween('date', [$request->start_date, $request->end_date]);
            })
            ->when($request->filled('fk_supplier_id'), function($q) use($request){
                $q->whereHas('sale.sale_details.product', function($product) use($request){
                    $product->where('supplier_id', $request->fk_supplier_id);
                });
            })
            ->when($request->filled('type'), function($q) use($request){

                $q->whereHas($request->type);

                if($request->type == "returns") {
                    $q->whereDoesntHave('wastages');
                }
            });

            return $sale_returns;
    }
}
