<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierTransacionsController extends Controller
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
        $suppliers = Supplier::suppliers()->pluck('name', 'id');
        $supplier = '';

        $transactions = Transaction::with('balance')
        ->whereIn('transactionable_type', [Purchase::class, AccountReview::class])
        ->when($request->filled('from'), function($transaction){
            // $transaction->whereBetween('date', dateRange());
        })
        ->whereHasMorph('transactionable', AccountReview::class, function ($q)use($request){
            $q->where('transactionable_type', Supplier::class)
                ->when($request->filled('supplier_id'), function($qry) use($request){
                    $qry->where('transactionable_id', $request->supplier_id);
                })
                 ->whereBetween('date', dateRange())    
                ;
        })
        ->orWhereHasMorph('transactionable', Purchase::class, function ($q)use($request){
                    $q->when($request->filled('supplier_id'), function($query) use($request){
                        $query->where('fk_supplier_id', $request->supplier_id);
                    })->whereBetween('date', dateRange());
            });

    }
}
