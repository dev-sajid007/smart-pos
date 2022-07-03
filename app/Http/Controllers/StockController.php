<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductStock;
use App\Supplier;
use Illuminate\Http\Request;

class StockController extends Controller
{
    
    
    public function report(Request $request)
    {
        $data['suppliers'] = Supplier::companies()->pluck('name', 'id');

        $data['totalProductPrice'] = Product::companies()
                                    ->with('product_stock')
                                    ->when($request->filled('fk_supplier_id'), function ($q) use ($request) {
                                        $q->where('supplier_id', $request->fk_supplier_id);
                                    })
                                    ->get()->map(function ($item) {
                                        return (optional($item->product_stock)->available_quantity ?? 0) * $item->product_cost;
                                    })->sum();

        $report = $this->getReport($request);

        $data['stocks']    = $report['stocks']->paginate(30);
        $data['supplier']  = $report['supplier'];

        return view('admin.reports.supplier_wise_stock', $data);
    }


    public function stockPrint()
    {
        $request = request();
        $stocks = $this->getReport($request)['stocks'];
        $supplier = $this->getReport($request)['supplier'];

        return view('admin.reports.supplier_wise_stock_print', [
            'stocks' => $stocks->get(),
            'supplier' => $supplier
            ]);
    }


    private function getReport($request)
    {

        $stocks = ProductStock::companies()->with('product','product.category_name', 'product.supplier');

        $supplier = '';

        if ($request->filled('fk_supplier_id')) {
            $stocks->whereHas('product', function ($q) use ($request) {
                $q->where('supplier_id', $request->fk_supplier_id);
            });

            $supplier = Supplier::findOrFail($request->fk_supplier_id);
        }

        return [
            'stocks' => $stocks,
            'supplier' => $supplier
        ];
    }

    public function getProductSerials(Request $request)
    {
        $product = Product::with(['product_serials' => function($q) use($request) {
            $q
            // ->where('is_sold', 0)
            ->where('warehouse_id', $request->warehouse_id);
        }])->where('id', $request->product_id)->first();

        return view('admin.reports.inventory-reports.stock-roduct-serials', compact('product'));
        return $product;
        return $request->all();
    }
}
