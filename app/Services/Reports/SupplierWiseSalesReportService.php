<?php


namespace App\Services\Reports;


use App\Sale;
use App\Supplier;

class SupplierWiseSalesReportService
{
    public function getSupplierWiseSalesReportData()
    {
        $request = request();

        $data['suppliers'] = Supplier::companies()->pluck('name', 'id');

        $data['sales'] = Sale::companies()->with('sale_details', 'sale_details.product')
            ->whereBetween('sale_date', [$request->start_date, $request->end_date])
            ->when($request->filled('fk_supplier_id'), function($q) use($request){
                $q->whereHas('sale_details.product', function($product) use($request){
                    $product->where('supplier_id', $request->fk_supplier_id);
                });
            })
            ->select('id', 'sale_date', 'invoice_discount')
            ->get();
        return $data;
    }
}
