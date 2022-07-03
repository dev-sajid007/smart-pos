<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountReview;
use App\Balance;
use App\Customer;
use App\Models\Inventory\Wastage;
use App\Models\RandomReturn;
use App\Product;
use App\Purchase;
use App\PurchaseDetails;
use App\Sale;
use App\SalesDetails;
use App\Supplier;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CustomerTransactionController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return $this->report();
    }

    private function report(){
        $request = \request();
        $transactions = Transaction::with('balance')
        ->whereIn('transactionable_type', [Sale::class, AccountReview::class, RandomReturn::class])
        ->when($request->filled('from'), function($transaction){
            $transaction->whereBetween('date', dateRange());
        })
        ->whereHasMorph('transactionable', AccountReview::class, function ($q)use($request){
            $q->where('transactionable_type', Customer::class)
                ->when($request->filled('customer_id'), function($qry) use($request){
                    $qry->where('transactionable_id', $request->customer_id);
                })
                ->whereBetween('date', dateRange());
        })
        ->orWhereHasMorph('transactionable', Sale::class, function ($q)use($request){
                    $q->when($request->filled('customer_id'), function($query) use($request){
                        $query->where('fk_customer_id', $request->customer_id);
                    });
            })
        ->orWhereHasMorph('transactionable', RandomReturn::class, function($q) use($request){
                $q->with('sumItemsAmount')->whereBetween('date', dateRange())
                    ->when($request->filled('customer_id'), function($qry) use($request){
                        $qry->where('fk_customer_id', $request->customer_id);
                    });
            });

     return $transactions->get();
    }
}
