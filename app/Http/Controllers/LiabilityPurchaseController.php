<?php

namespace App\Http\Controllers;

use App\Liability;
use App\LiabilityPurchase;
use App\LiabilityPurchaseDetail;
use Illuminate\Http\Request;

class LiabilityPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $liabilityPurchases = LiabilityPurchase::latest()->get();
        return view('admin.account.liability-purchases.index', compact('liabilityPurchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $liabilities = Liability::orderBy('name')->pluck('name', 'id');
        return view('admin.account.liability-purchases.create', compact('liabilities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (array_sum($request->amounts) > 0) {
          
            $purchase = LiabilityPurchase::create([
                'date'          => fdate(today(), 'Y-m-d'),
                'description'   => $request->description,
                'amount'        => 0
            ]);

            foreach($request->amounts as $key => $amount) {
                LiabilityPurchaseDetail::create([
                    'particular' => $request->particulars[$key],
                    'amount' => $amount,
                    'liability_id' => $request->liability_ids[$key],
                    'company_id' => auth()->user()->fk_company_id,
                    'liability_purchase_id' => $purchase->id
                ]);
            }
            $totalAmount = LiabilityPurchaseDetail::where('liability_purchase_id', $purchase->id)->sum('amount');

            $purchase->update([
                'amount' => $totalAmount
            ]);

            foreach($purchase->details as $key => $detail) {
                $this->updateliabilityAmount($detail->liability_id);
            }
            return redirect()->route('liability-purchases.index')->withSuccess('Liability purchase successful');
            
        }
    }

    private function updateLiabilityAmount($liability_id)
    {
        $totalLiabilityAmount = LiabilityPurchaseDetail::where('liability_id', $liability_id)->sum('amount');
        $liability = Liability::find($liability_id);
        $liability->update([
            'current_balance' => ($liability->opening + $totalLiabilityAmount)
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\LiabilityPurchase  $liabilityPurchase
     * @return \Illuminate\Http\Response
     */
    public function show(LiabilityPurchase $liabilityPurchase)
    {
        return view('admin.account.liability-purchases.show', compact('liabilityPurchase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LiabilityPurchase  $liabilityPurchase
     * @return \Illuminate\Http\Response
     */
    public function edit(LiabilityPurchase $liabilityPurchase)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LiabilityPurchase  $liabilityPurchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LiabilityPurchase $liabilityPurchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LiabilityPurchase  $liabilityPurchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(LiabilityPurchase $liabilityPurchase)
    {
        $liability_ids = $liabilityPurchase->details->pluck('liability_id');
        $liabilityPurchase->delete();

        foreach($liability_ids as $key => $liability_id) {
            $this->updateLiabilityAmount($liability_id);
        }
        return redirect()->back()->withSuccess('Liability purchase successfully deleted');
    }
}
