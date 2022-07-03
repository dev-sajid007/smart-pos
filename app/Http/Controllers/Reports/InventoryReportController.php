<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SoftwareSetting;
use App\Warehouse;
use App\Category;
use App\Product;
use App\Brand;
use App\Customer;
use App\Sale;
use App\SalesDetails;
use DB;

class InventoryReportController extends Controller
{

    public function topsellproduct(Request $request)
    {
        $data['count'] = 0;
        $data['selectproducts'] = Product::companies()->get();


        $data['totalQuantitiesPerProduct'] = SalesDetails::groupBy('fk_product_id')
        ->select('fk_product_id')
        ->selectRaw('sum(quantity) as totalQuantityPerProduct')
        ->with('product')
        ->get();

        $data['salesDetails'] = SalesDetails::with('sale')
        ->when($request->filled('start_date') || $request->filled('end_date'), function ($p) use ($request) {
            $p->whereHas('sale',function($q) use($request){
                $q->whereBetween('sale_date', [$request->start_date, $request->end_date]);
            });
        })
        ->when($request->filled('fk_product_id'), function ($q) use ($request) {
            $q->where('fk_product_id', $request->fk_product_id);
        })->get();
        return view('admin.reports.inventory-reports.topSellProduct', $data);

        // return Product::has('sales_details')->with('sales_details')->get();

    }

    public function productStockReport(Request $request)
    {
        $data['selectproducts'] = Product::pluck('product_name', 'id');
        $data['categories']     = Category::with('subcategories')->get();
        $data['brands']         = Brand::pluck('name', 'id');
        $data['warehouses']     = Warehouse::companies()->pluck('name', 'id');
        $data['settings']       = SoftwareSetting::companies()->get();
        $products = Product::
            when($request->filled('brand_id'), function ($q) use($request) {
                $q->where('brand_id', $request->brand_id);
            })
            ->when($request->filled('category_id'), function ($q) use($request) {
                $q->where('fk_category_id', $request->category_id);
            })
            ->when($request->filled('fk_sub_category_id'), function ($q) use($request) {
                $q->where('fk_sub_category_id', $request->fk_sub_category_id);
            })
            ->when($request->filled('product_id'), function ($q) use($request) {
                $q->where('id', $request->product_id);
            })
            ->when($request->warehouse_id == 'all', function ($q) use ($request) {
                $q->withCount(['warehouse_stocks as opening_quantity' => function($q) use($request) {
                    $q->select(DB::Raw('SUM(opening_quantity)'));
                }])->withCount(['warehouse_stocks as purchased_quantity' => function($q) use($request) {
                    $q->select(DB::Raw('SUM(purchased_quantity)'));
                }])->withCount(['warehouse_stocks as sold_quantity' => function($q) use($request) {
                    $q->select(DB::Raw('SUM(sold_quantity)'));
                }])->withCount(['warehouse_stocks as wastage_quantity' => function($q) use($request) {
                    $q->select(DB::Raw('SUM(wastage_quantity)'));
                }])->withCount(['warehouse_stocks as transfer_out' => function($q) use($request) {
                    $q->select(DB::Raw('SUM(transfer_out)'));
                }])->withCount(['warehouse_stocks as transfer_in' => function($q) use($request) {
                    $q->select(DB::Raw('SUM(transfer_in)'));
                }])->withCount(['warehouse_stocks as available_quantity' => function($q) use($request) {
                    $q->select(DB::Raw('SUM(available_quantity)'));
                }]);

            })->when($request->warehouse_id != 'all', function ($qr) use ($request) {

                $qr->withCount(['warehouse_stocks as opening_quantity' => function ($q) use ($request) {
                    $q->where('warehouse_id', $request->warehouse_id)->select(DB::Raw('SUM(opening_quantity)'));
                }])->withCount(['warehouse_stocks as purchased_quantity' => function ($q) use ($request) {
                    $q->where('warehouse_id', $request->warehouse_id)->select(DB::Raw('SUM(purchased_quantity)'));
                }])->withCount(['warehouse_stocks as sold_quantity' => function ($q) use ($request) {
                    $q->where('warehouse_id', $request->warehouse_id)->select(DB::Raw('SUM(sold_quantity)'));
                }])->withCount(['warehouse_stocks as wastage_quantity' => function ($q) use ($request) {
                    $q->where('warehouse_id', $request->warehouse_id)->select(DB::Raw('SUM(wastage_quantity)'));
                }])->withCount(['warehouse_stocks as transfer_out' => function ($q) use ($request) {
                    $q->where('warehouse_id', $request->warehouse_id)->select(DB::Raw('SUM(transfer_out)'));
                }])->withCount(['warehouse_stocks as transfer_in' => function ($q) use ($request) {
                    $q->where('warehouse_id', $request->warehouse_id)->select(DB::Raw('SUM(transfer_in)'));
                }])->withCount(['warehouse_stocks as available_quantity' => function ($q) use ($request) {
                    $q->where('warehouse_id', $request->warehouse_id)->select(DB::Raw('SUM(available_quantity)'));
                }]);
            });

        $data['total_product_price'] = $products->get()->map(function ($item) {
            return ($item->available_quantity ?? 0) * $item->product_cost;
        })->sum();

        $data['products'] = $products->paginate(30);

        return view('admin.reports.inventory-reports.product-stocks', $data);
    }
}
