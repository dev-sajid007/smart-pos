<?php

namespace App\Http\Controllers\Reports;

use App\AccountReview;
use App\Http\Controllers\Controller;
use App\Purchase;
use App\Sale;
use App\Services\PurchaseService;
use App\Services\Reports\SupplierReportServices;
use App\Supplier;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayableDueReportController extends Controller
{
    private $purchases = [];
    private $transactions = [];


    public function getSupplierTransactions()
    {
        $transactions = Transaction::whereHasMorph('transactionable', AccountReview::class, function ($q) {
            $q->where('transactionable_type', Supplier::class);
        })->with('transactionable')
            ->get();

        $transactions = $transactions
            ->map(function($item) {
                $item['fk_supplier_id'] = $item->transactionable->transactionable_id;
                return $item;
            });
        $this->transactions = $transactions;
        return $transactions;
    }


    public function getPurchases()
    {
         $purchases = Purchase::withCount(['purchase_details AS total_amount' => function ($query) {
                $query->select(DB::raw("SUM(product_sub_total) as total"));
            }])->get();
        $this->purchases = $purchases;
    }

    public function getPaymentAmount($supplier_id)
    {
        return (-1) * $this->transactions->where('fk_supplier_id', $supplier_id)->sum('amount');
    }

    public function getPurchaseAmount($supplier_id)
    {
        return $this->purchases->where('fk_supplier_id', $supplier_id)->sum(function ($item) {
            return $item->total_amount - ($item->invoice_discount + $item->paid_amount);
        });
    }
}
