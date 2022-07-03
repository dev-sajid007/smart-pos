<?php


namespace App\Services\Reports;


use App\Customer;
use App\Sale;
use App\SalesDetails;

class SalesReportService
{

    public function getSalesReportData()
    {
        $request = request();

        $count = 0;

        // for special purpose of profit report
        if ($request->filled('from')) {
            $saleDetails = SalesDetails::skip($request->from - 1)->take($request->count ?? 500)->with('product:id,product_cost')->get();
            foreach ($saleDetails as $key => $detail) {
                if($detail->product_cost <= 0) {
                    $detail->update(['product_cost' => $detail->product->product_cost]);
                    $count++;
                }
            }
        }


        $data['count'] = $count;
        $data['customers'] = Customer::companies()->get(['name', 'id']);

        $data['sales'] = Sale::companies()
            ->select('id', 'sale_date', 'fk_customer_id', 'invoice_discount')
            ->with('sale_customer:name,id')
            ->when($request->filled('start_date') || $request->filled('end_date'), function ($q) use ($request) {
                $q->whereBetween('sale_date', [$request->start_date, $request->end_date]);
            })

            ->when($request->filled('fk_customer_id'), function ($q) use ($request) {
                $q->where('fk_customer_id', $request->fk_customer_id);
            })->withCount(['sale_details AS total_purchase_cost' => function ($q) {
                return $q->select(\DB::Raw('SUM(product_cost * quantity)'));
            }])
            ->withCount(['sale_details AS total_sale_price' => function ($q) {
                return $q->select(\DB::Raw('SUM(unit_price * quantity)'));
            }])
            ->orderBy('sale_date')
            ->get() ?? collect([]);

        return $data;
    }
}
