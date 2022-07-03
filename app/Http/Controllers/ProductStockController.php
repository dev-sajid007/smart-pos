<?php

namespace App\Http\Controllers;

use App\Category;
use App\Sale;
use App\Supplier;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use App\ProductStock;
use App\Purchase;
use App\PurchaseDetails;
use App\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductStockController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }



    public function filter(Request $request)
    {
        $filters = ProductStock::companies()->paginate(10);
        return redirect('reports/product_stock')->with(['filters' => $filters]);
    }


    //My Function (Pranto)

    public function date_product_reports(Request $request)
    {
        $request->validate([
            'product' => 'required',
            'date' => 'required'
        ]);

        $products = Product::companies()->pluck('product_name', 'id');

        $purchaseQuantity = DB::table('purchases')
            ->join('purchase_details', 'purchases.id', '=', 'purchase_details.fk_purchase_id')
            ->where('purchase_date', '=', $request->date)
            ->where('fk_product_id', '=', $request->product)
            ->where('fk_company_id', auth()->user()->fk_company_id)
            ->selectRaw('sum(quantity) as totalQuantity')->selectRaw('sum(free_quantity) as totalFreeQuantity')
            ->first();

        $salesQuantity = DB::table('sales')
            ->join('sales_details', 'sales.id', '=', 'sales_details.fk_sales_id')
            ->where('sale_date', '=', $request->date)
            ->where('fk_product_id', '=', $request->product)
            ->where('fk_company_id', auth()->user()->fk_company_id)
            ->selectRaw('sum(quantity) as totalSealsQuantity')
            ->first();

        return view('admin.reports.day_wise_product_report', compact('products','purchaseQuantity', 'salesQuantity'));


    }


    public function get_json_stock(Request $request)
    {
        if ($request->ajax()) {
            $data = PurchaseDetails::with(['product', 'sales_details', 'product_stock'])
                ->where('fk_company_id', auth()->user()->fk_company_id)
                ->selectRaw('fk_product_id, sum(quantity) as total_purchased')
                ->groupBy('fk_product_id');

            if ($request->filled('id')) {
                $data->whereHas('product', function ($query_product) use ($request) {
                    $query_product->whereId($request->id);
                });
            }

            if ($request->filled('purchase_date')) {
                $data->whereHas('purchase', function ($query) use ($request) {
                    $query->wherePurchaseDate($request->purchase_date);
                });
            }

            if ($request->filled('sale_date')) {
                $data->whereHas('sales_details.sale', function ($qry) use ($request) {
                    $qry->whereSaleDate($request->sale_date);
                });

            }

            $response = $data->get();

            return response()->json($response);

        }
    }

    public function stockAlert()
    {
        $stock = ProductStock::with('stock_product:product_name,id')
            ->where('fk_company_id', companyId())
            ->whereHas('stock_product', function ($q){
                $q->whereColumn('product_alert_quantity', '>=', 'available_quantity');
            })
            ->get(['available_quantity', 'fk_product_id']);
        return response($stock, 200);
    }

    public function expireProducts(Request $request)
    {
        $data['stocks'] = collect([]);
        $data['categories'] = Category::companies()->pluck('category_name', 'id');
        $data['suppliers'] = Supplier::companies()->pluck('name', 'id');

        if ($request->filled('date')) {
            $data['stocks'] = ProductStock::companies()->with('product', 'product.supplier:name,id', 'product.category:category_name,id')->latest()
                ->when($request->filled('fk_category_id'), function ($q) use ($request) {
                    $q->whereHas('product', function ($qr) use ($request) {
                        $qr->where('fk_category_id', $request->fk_category_id);
                    });
                })
                ->when($request->filled('fk_supplier_id'), function ($q) use ($request) {
                    $q->whereHas('product', function ($qr) use ($request) {
                        $qr->where('supplier_id', $request->fk_supplier_id);
                    });
                })
                ->withCount(['purchase_details as total_valid_quantity' => function($q) use($request) {
                $q->where('expire_date', '>=', $request->date)->select(DB::Raw('SUM(quantity)'));
            }])->get();
        }

        return view('admin.reports.expire-products', $data);
    }
}
