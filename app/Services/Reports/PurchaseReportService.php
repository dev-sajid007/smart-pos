<?php


namespace App\Services\Reports;


use App\Purchase;
use App\Supplier;

class PurchaseReportService
{

    public function getPurchasesReportData()
    {
        $request = request();

        $data['suppliers'] = Supplier::companies()->get(['name', 'id']);

        $data['purchases'] = Purchase::companies()
                ->select('id', 'purchase_date', 'fk_supplier_id', 'invoice_discount')
                ->with('supplier:name,id')
                ->when($request->filled('start_date') || $request->filled('end_date'), function ($q) use ($request) {
                    $q->whereBetween('purchase_date', [$request->start_date, $request->end_date]);
                })

                ->when($request->filled('fk_supplier_id'), function ($q) use ($request) {
                    $q->where('fk_supplier_id', $request->fk_supplier_id);
                })
                ->withCount(['purchase_details AS total_purchase_price' => function ($q) {
                    return $q->select(\DB::Raw('SUM(unit_price * quantity)'));
                }])
                ->orderBy('purchase_date')
                ->get() ?? collect([]);

        return $data;
    }

}
