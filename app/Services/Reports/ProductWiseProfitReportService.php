<?php


namespace App\Services\Reports;


use App\Brand;
use App\Category;
use App\Product;

class ProductWiseProfitReportService
{
    public function getProductWiseProfitReportData()
    {
        $request = request();

        $data['brands']             = Brand::companies()->pluck('name', 'id');
        $data['categories']         = Category::companies()->pluck('category_name', 'id');
        $data['select_products']    = Product::companies()->pluck('product_name', 'id');

        $data['products']      = Product::companies()->with('category_name', 'brand')->withCount(['sales_details AS total_product_cost' => function($q) {
            return $q->select(\DB::Raw('SUM(product_cost * quantity)'));
        }])->withCount(['sales_details AS total_sale_price' => function($q) {
            return $q->select(\DB::Raw('SUM(unit_price * quantity)'));
        }])
            ->when($request->filled('category_id'), function ($q) use($request) {
                $q->where('fk_category_id', $request->category_id);
            })
            ->when($request->filled('brand_id'), function ($q) use($request) {
                $q->where('brand_id', $request->brand_id);
            })
            ->when($request->filled('product_id'), function ($q) use($request) {
                $q->where('id', $request->product_id);
            })
            ->when($request->filled('from'), function ($q) use ($request) {
                $q->whereHas('sales_details.sale', function ($qr) use ($request) {
                    $qr->where('sale_date', '>=', $request->from);
                });
            })
            ->when($request->filled('to'), function ($q) use ($request) {
                $q->whereHas('sales_details.sale', function ($qr) use ($request) {
                    $qr->where('sale_date', '<=', $request->to);
                });
            })->latest()->get();

        return $data;
    }
}
