<?php

namespace App\Http\Controllers\Reports;

use App\Account;
use App\AccountReview;
use App\Balance;
use App\Brand;
use App\Category;
use App\Customer;
use App\Http\Controllers\Controller;
use App\Models\Inventory\Wastage;
use App\Models\RandomReturn;
use App\Party;
use App\Product;
use App\Purchase;
use App\PurchaseDetails;
use App\Sale;
use App\SalesDetails;
use App\Services\Reports\CashFlowReportService;
use App\Services\Reports\CustomerReportServices;
use App\Services\Reports\ProductWiseProfitReportService;
use App\Services\Reports\PurchaseReportService;
use App\Services\Reports\SalesReportService;
use App\Services\Reports\SupplierReportServices;
use App\Services\Reports\SupplierWiseSalesReportService;
use App\Services\Reports\WastageProductReportService;
use App\Supplier;
use App\Transaction;
use App\Voucher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use DB;
class ReportController extends Controller
{

    public function salesReport(Request $request)
    {
        $data = (new SalesReportService())->getSalesReportData();

        return view('admin.reports.sales_reports', $data);
    }



    public function supplierWiseSalesReport(Request $request)
    {
        $data = (new SupplierWiseSalesReportService())->getSupplierWiseSalesReportData();

        return view('admin.reports.supplier_wise_sales_report', $data);
    }

    public function dailReport()
    {
        $data['todaySales'] = Product::companies()->with('todaySold')->whereHas('todaySold')->get();
        $data['todayPurchases'] = Product::companies()->with('todayPurchased')->whereHas('todayPurchased')->get();

        return view('admin.reports.daily_reports', $data);
    }



    public function wastageReport(Request $request)
    {
        $data = (new WastageProductReportService())->getWastageProductData($request);

        return view('admin.reports.wastage', $data);
    }

    public function taxReport(Request $request)
    {
        $from = $request->start_date ?? "2010-01-01";
        $to = $request->end_date ?? Carbon::parse(now())->format('Y-m-d');

        $customers = Customer::companies()->pluck('name', 'id');
        $sales = Sale::companies()->where('invoice_tax', '>', 0)
            ->whereBetween('sale_date', [$from, $to])
            ->when($request->filled('fk_customer_id'), function($q) use($request) {
                $q->where('fk_customer_id', $request->fk_customer_id);
            })->get();

        return view('admin.reports.tax_reports', compact('customers', 'sales'));
    }

    public function productWiseProfitReport(Request $request)
    {
        $data = (new ProductWiseProfitReportService())->getProductWiseProfitReportData();

        return view('admin.reports.product_wise_profit_report', $data);
    }

    public function purchaseReport(Request $request)
    {
        $data = (new PurchaseReportService())->getPurchasesReportData();

        return view('admin.reports.purchase_reports', $data);
    }

    public function gaPartiesReport(Request $request)
    {
        $parties = Party::companies()->pluck('name', 'id');
        $reports = Voucher::companies()->select('voucher_date', 'voucher_type', 'voucher_reference')

            ->when($request->filled('from'), function ($q) use ($request) {
                    $q->where('voucher_date', '>=', $request->from);
            })
            ->when($request->filled('to'), function ($q) use ($request) {
                $q->where('voucher_date', '<=', $request->to);
            })
            ->when($request->filled('party_id'), function ($q) use ($request) {
                $q->where('fk_party_id', $request->party_id);
            })
            ->totalAmount()->get();
        return view('admin.reports.ga-parties-report', compact('parties', 'reports'));
    }
}



