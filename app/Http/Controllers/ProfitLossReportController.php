<?php

namespace App\Http\Controllers;

use App\Account;
use App\GlAccount;
use App\Models\RandomReturn;
use App\Product;
use App\ProductStock;
use Illuminate\Http\Request;
use App\AccountChart;
use App\AccountReview;
use App\Customer;
use App\Purchase;
use App\Sale;
use App\Supplier;
use App\Transaction;
use App\VoucherChartPayment;
use Illuminate\Support\Facades\DB;

class ProfitLossReportController extends Controller
{
    public function index(Request $request)
    {
//        $from   = $request->from ?? '2000-01-01';
//        $to     = $request->to ?? date('Y-m-d');
//
//        $data['collections'] = Transaction::where('amount', '>', 0)
//            ->whereIn('transactionable_type', ['App\AccountReview', 'App\Sale'])
//            ->whereDate('date', '>=', $from)
//            ->whereDate('date', '<=', $to)
//            ->sum('amount');
//
//        $data['payments'] = Transaction::where('amount', '<', 0)
//            ->whereIn('transactionable_type', ['App\AccountReview', 'App\Purchase'])
//            ->whereDate('date', '>=', $from)
//            ->whereDate('date', '<=', $to)
//            ->sum('amount');
//
//        $data['incomes'] = Transaction::where('amount', '>', 0)
//            ->whereIn('transactionable_type', ['App\VoucherChartPayment'])
//            ->whereDate('date', '>=', $from)
//            ->whereDate('date', '<=', $to)
//            ->sum('amount');
//
//        $data['expenses'] = Transaction::where('amount', '<', 0)
//            ->whereIn('transactionable_type', ['App\VoucherChartPayment'])
//            ->whereDate('date', '>=', $from)
//            ->whereDate('date', '<=', $to)
//            ->sum('amount');


        // ==========================================


        $inStock = $this->getTotalProductPriceInStock();
        $saleProfit = $this->getTotalSalePrice();

        $data['customerReceivableDue']              = $this->getReceivableDue();
        $data['supplierPayableDue']                 = $this->getPayableDue();
        $data['totalSalePrice']                     = $saleProfit[1];
        $data['totalPurchasePrice']                 = $saleProfit[0];
        $data['totalOpeningProductValue']           = $this->getTotalOpeningProductValue();
        $data['totalPurchaseProductPriceInStock']   = $inStock[0];
        $data['totalSaleProductPriceInStock']       = $inStock[1];
        $data['accountTransactions']                = $this->getAccountsTransactions();
        $data['glAccountExpense']                   = $this->getGlAccountExpense();
        $data['glAccountAsset']                     = 0;




        return view('admin.reports.profit_loss_report', $data);

    }

    private function getTotalSalePrice()
    {
        $sales = Sale::withCount(['sale_details as total_sale_amount' => function($q) {
            $q->select(DB::raw('sum(quantity * unit_price)'));
        }])->withCount(['sale_details as total_purchase_amount' => function($q) {
            $q->select(DB::raw('sum(quantity * product_cost)'));
        }])->get();

        $purchase_price = $sales->sum('total_purchase_amount');

        $sale_price = $sales->sum(function ($item) {
            return $item->total_sale_amount - $item->invoice_discount;
        });

        return [$purchase_price, $sale_price];

        return Sale::withCount(['sale_details as total_amount' => function($q) {
            $q->select(DB::raw('sum(quantity * unit_price)'));
        }])->get()->sum(function ($item) {
            return $item->total_amount - $item->invoice_discount;
        });
    }

    private function getTotalPurchasePrice()
    {
        return Purchase::withCount(['purchase_details AS total_amount' => function ($query) {
            $query->select(DB::raw("sum(quantity * unit_price)"));
        }])->get()->sum(function ($item) {
            return ($item->total_amount - $item->invoice_discount);
        });
    }

    private function getTotalOpeningProductValue()
    {
        return Product::get()->sum(function ($item) {
            return $item->opening_quantity * $item->product_cost;
        });
    }

    private function getTotalProductPriceInStock()
    {
//        $totalProductValue = Product::whereDoesntHave('product_stock')->where('opening_quantity', '>', 0)->get()
//            ->sum(function ($item) {
//                return $item->opening_quantity * $item->product_cost;
//            });

//        return Product::first();
        $productStock = ProductStock::with('stock_product')->get();

        $purchasePrice = $productStock->sum(function ($item) {
                return $item->available_quantity * $item->stock_product->product_cost;
            });

        $salePrice = $productStock->sum(function ($item) {
                return $item->available_quantity * $item->stock_product->product_price;
            });
        return [$purchasePrice, $salePrice];
    }

    private function getAccountsTransactions()
    {
        return Account::withCount(['transactions as total_amount' => function($q) {
            $q->select(DB::raw('sum(amount)'));
        }])->get();
    }

    private function getGlAccountExpense()
    {
        return Account::withCount(['income_transactions as total_income' => function($q) {
            $q->select(DB::raw('sum(amount)'));
        }])->withCount(['expense_transactions as total_expense' => function($q) {
            $q->select(DB::raw('sum(amount)'));
        }])->get()->sum('total_expense');
    }

    private function getReceivableDue()
    {
        $sales_amount           = Sale::withCount(['sale_details as total_amount' => function($q) {
            $q->select(DB::raw('sum(quantity * unit_price)'));
        }])->get()->sum(function ($item) {
            return $item->total_amount - $item->invoice_discount;
        });

        $collections            = $this->getReceivableTransactions();
        $customers_previous_due = Customer::sum('customer_previous_due');


        return $receivable_due = ($customers_previous_due + $sales_amount - $collections);
    }

    private function getPayableDue()
    {
        $payments               = $this->getPayableTransactions();
        $purchase_amounts       = $this->getTotalPurchasePrice();
        $supliers_previous_due = Supplier::sum('opening_due');

        return $receivable_due = $supliers_previous_due + $purchase_amounts - $payments;
    }

    private function getReceivableTransactions()
    {
        $transactions = Transaction::
            whereHasMorph('transactionable', AccountReview::class, function ($q) {
                $q->where('transactionable_type', Customer::class);
            })
            ->orWhereHasMorph('transactionable', Sale::class)
            ->orWhereHasMorph('transactionable', RandomReturn::class)->sum('amount');
    }

    private function getPayableTransactions()
    {
        return $transactions = Transaction::whereHasMorph('transactionable', AccountReview::class, function ($q) {
                                        $q->where('transactionable_type', Supplier::class);
                                    })
                                    ->orWhereHasMorph('transactionable', Purchase::class)
                                    ->orWhereHasMorph('transactionable', RandomReturn::class)->sum('amount');
    }
}
