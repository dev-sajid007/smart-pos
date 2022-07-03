<?php

namespace App\Http\Controllers;

use App\Company;
use App\Models\Inventory\InventoryReturn;
use App\Models\Inventory\Wastage;
use App\ProductStock;
use App\PurchaseReturn;
use App\PurchaseReturnReceive;
use Illuminate\Http\Request;
use DB;

class PurchaseReturnReceiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchaseReturnReceives = PurchaseReturnReceive::companies()->userLog()->with('purchase_return:invoice_id,id', 'receive_details')->latest()
            ->withCount(['receive_details AS total_quantity' => function ($query) {
                $query->select(DB::raw("SUM(quantity) as total"));
            }])->paginate(30);

        return view('admin.purchases.return-receives.index', compact('purchaseReturnReceives'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $purchaseReturn = [];

        $purchaseReturnIds = PurchaseReturn::companies()->pluck('invoice_id', 'id');
        if (count($request->all()) > 0) {
            $purchaseReturn = PurchaseReturn::with('purchaseReturnDetails.product', 'purchase_return_receives.receive_details')->find($request->purchase_return_id);
        }
        return view('admin.purchases.return-receives.create', compact('purchaseReturnIds', 'purchaseReturn'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $purchaseReturnReceive = PurchaseReturnReceive::create([
                'company_id'            => auth()->user()->fk_company_id ?? Company::first()->id,
                'supplier_id'           => PurchaseReturn::find($request->purchase_return_id)->supplier_id,
                'purchase_return_id'    => $request->purchase_return_id,
                'date'                  => $request->date,
                'created_by'            => auth()->id(),
            ]);

            $this->storeDetails($purchaseReturnReceive);
            return back()->withSuccess('Return Receive Successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->withError($ex->getMessage());
        }
    }

    private function storeDetails($purchaseReturnReceive)
    {
        $request = \request();

        foreach ($request->receive_quantities as $key => $quantity) {
            if ($quantity != '' && $quantity != 0) {
                $item = $purchaseReturnReceive->receive_details()->create([
                    'product_id' => $request->product_ids[$key],
                    'quantity'   => $quantity,
                    'condition'   => $request->conditions[$key]
                ]);


                $this->updateStock($item);
            }
        }
    }


    private function updateStock($item)
    {
        $stock = ProductStock::where('fk_product_id', $item->product_id)->first();


        if (!$stock){
            $stock = ProductStock::create([
                'fk_product_id' => $item->product_id
            ]);
        }

        $available = $stock->available_quantity + $item->quantity;


        $stock->update([
            'available_quantity'    => $available + 0,
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchaseReturnReceive = PurchaseReturnReceive::find($id);
        return view('admin.purchases.return-receives.show', compact('purchaseReturnReceive'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            \DB::transaction(function () use ($id) {
                $purchaseReturnReceive = PurchaseReturnReceive::find($id);

                foreach ($purchaseReturnReceive->receive_details as $key => $detail) {
                    $this->updateStockForDelete($detail);
                    $detail->delete();
                }
                $purchaseReturnReceive->delete();
            });
            return back()->withSuccess('Purchase Return Receive Deleted Successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->withError($ex->getMessage());
        }
    }




    private function updateStockForDelete($item)
    {
        $stock = ProductStock::where('fk_product_id', $item->product_id)->first();

        $available = $stock->available_quantity - $item->quantity;


        // add stock to product stock table
        $stock->update([
            'available_quantity'    => $available + 0,
        ]);
    }
}
