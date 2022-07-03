<?php

namespace App\Http\Controllers;

use App\Asset;
use App\AssetPurchase;
use App\AssetPurchaseDetail;
use Illuminate\Http\Request;

class AssetPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assetPurchases = AssetPurchase::latest()->get();
        return view('admin.account.asset-purchases.index', compact('assetPurchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $assets = Asset::orderBy('name')->pluck('name', 'id');
        return view('admin.account.asset-purchases.create', compact('assets'));
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
          
            $purchase = AssetPurchase::create([
                'date'          => fdate(today(), 'Y-m-d'),
                'description'   => $request->description,
                'amount'        => 0
            ]);

            foreach($request->amounts as $key => $amount) {
                AssetPurchaseDetail::create([
                    'particular' => $request->particulars[$key],
                    'amount' => $amount,
                    'asset_id' => $request->asset_ids[$key],
                    'company_id' => auth()->user()->fk_company_id,
                    'asset_purchase_id' => $purchase->id
                ]);
            }
            $totalAmount = AssetPurchaseDetail::where('asset_purchase_id', $purchase->id)->sum('amount');

            $purchase->update([
                'amount' => $totalAmount
            ]);

            foreach($purchase->details as $key => $detail) {
                $this->updateAssetAmount($detail->asset_id);
            }
            return redirect()->route('asset-purchases.index')->withSuccess('Asset purchase successful');
            
        }
    }

    private function updateAssetAmount($asset_id)
    {
        $totalAssetAmount = AssetPurchaseDetail::where('asset_id', $asset_id)->sum('amount');
        $asset = Asset::find($asset_id);
        $asset->update([
            'current_balance' => ($asset->opening + $totalAssetAmount)
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\AssetPurchase  $assetPurchase
     * @return \Illuminate\Http\Response
     */
    public function show(AssetPurchase $assetPurchase)
    {
        return view('admin.account.asset-purchases.show', compact('assetPurchase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AssetPurchase  $assetPurchase
     * @return \Illuminate\Http\Response
     */
    public function edit(AssetPurchase $assetPurchase)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AssetPurchase  $assetPurchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssetPurchase $assetPurchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AssetPurchase  $assetPurchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssetPurchase $assetPurchase)
    {
        $asset_ids = $assetPurchase->details->pluck('asset_id');
        $assetPurchase->delete();

        foreach($asset_ids as $key => $asset_id) {
            $this->updateAssetAmount($asset_id);
        }
        return redirect()->back()->withSuccess('Asset purchase successfully deleted');
    }
}
