<?php


namespace App\Services\Reports;


use App\Customer;
use App\Models\Inventory\Wastage;
use App\Supplier;

class WastageProductReportService
{

    public function getWastageProductData($request)
    {
        $data['customers'] = Customer::companies()->pluck('name', 'id');
        $data['suppliers'] = Supplier::companies()->pluck('name', 'id');

        $wastages = Wastage::whereHas('randomReturnItem.product', function($product) use($request) {
            $product->where('fk_company_id', companyId());
        })->when ($request->filled('from'), function ($q) {
            $q->whereBetween(DB::raw('DATE(created_at)'), dateRange());
        })->when($request->filled('fk_customer_id'), function ($q) use($request) {
            $q->whereHas('randomReturnItem.randomReturn', function($qr) use($request) {
                $qr->where('fk_customer_id', $request->fk_customer_id);
            });
        })->when($request->filled('fk_supplier_id'), function ($q) use($request) {
            $q->whereHas('randomReturnItem.product', function($qr) use($request) {
                $qr->where('supplier_id', $request->fk_supplier_id);
            });
        });

        $fullWastage = clone $wastages;

        $data['wastages']      = $wastages->paginate(30);
        $data['wastagesFull']   = $fullWastage->get();

        return $data;
    }
}
