<?php

namespace App\Http\Controllers;

use DB;
use App\Account;
use App\Product;
use App\Purchase;
use App\Supplier;
use Carbon\Carbon;
use App\ProductStock;
use App\PurchaseReturn;
use Illuminate\Http\Request;
use App\Models\Inventory\Wastage;
use App\Services\PurchaseService;
use App\Models\Inventory\ProductDamage;
use App\Models\Inventory\InventoryReturn;
use App\Models\Inventory\ProductDamageItem;

class PurchaseReturnController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return PurchaseReturn::where('invoice_id', 'LIKE', "%{$request->name}%")->whereNull('is_amount_adjustment')->take(15)->pluck('invoice_id', 'id');
        }

        $purchaseReturns = PurchaseReturn::companies()->userLog()->with('supplier')
            ->withCount(['purchaseReturnDetails AS total_amount' => function ($query) {
                $query->select(DB::raw("SUM(amount * quantity) as total"));
            }])
            ->latest()->paginate(30);

        return view('admin.purchase.return.index', compact('purchaseReturns'));
    }

    
    public function create()
    {
        $data['suppliers']  = Supplier::companies()->select( 'id', 'name', 'current_balance as balance')->get();
        $data['accounts']   = Account::getAccountWithBalance();

        return view('admin.purchase.return.create', $data);
    }

    
    public function store(Request $request)
    {
        $purchaseReturnId = null;
        try {
            DB::transaction(function () use($request, &$purchaseReturnId) {

                $invoice = $this->makeInvoice($request);
    
                $purchaseReturnId = $invoice->id;
    
                $this->storePurchaseReturnDetails($invoice, $request);
    
    
                $this->makeTransaction($invoice, $request);
            });

            (new PurchaseService())->updateSupplierBalance($request->supplier_id);

            return redirect()->route('purchase-returns.show', $purchaseReturnId)->withSuccess('Purchase Return Successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->withError($ex->getMessage());
        }
    }



    private function makeInvoice($request)
    {
        $purchaseReturn = PurchaseReturn::create([
            'date'          => fdate(today(), 'Y-m-d'),
            'invoice_id'    => time(),
            'supplier_id'   => $request->supplier_id,
            'reference'     => $request->reference,
            'comment'       => $request->comment,
            'amount'        => $request->total_amount,
            'paid_amount'   => $request->return_amount,
            'previous_due'  => $request->previous_due,
        ]);

        
        $purchaseReturn->update([
            'invoice_id' => '#PR-' . (1000000 + $purchaseReturn->id)
        ]);
        return $purchaseReturn;
    }

    
    
    private function storePurchaseReturnDetails($invoice, $request)
    {
        // dd($request->all());
        foreach ($request->product_ids as $key => $product_id) {
            $item = $invoice->purchaseReturnDetails()->create([
                'product_id'        =>  $product_id,
                'quantity'          =>  $request->quantities[$key],
                'amount'            =>  $request->prices[$key],
                'condition'         =>  $request->condition_type[$key],
            ]);
            if($request->condition_type[$key]=='1'){
                 $this->stockManage($item);
            }
           else{
                $this->makeWastage($request,$key);
           }
        }
    }



    private function stockManage($item)
    {
        $stock = ProductStock::where('fk_product_id', $item->product_id)->where('fk_company_id', auth()->user()->fk_company_id)->whereNull('warehouse_id')->first();

        if (!$stock) {
            $stock = ProductStock::create([
                'fk_product_id'         => $item->product_id,
                'sold_quantity'         => -$item->quantity,
                'available_quantity'    => $item->quantity
            ]);
        } else {
            $stock->decrement('available_quantity', $item->quantity);
            $stock->increment('purchased_quantity', $item->quantity);
            $stock->save();
        }
    }
    
    private function makeWastage($request,$key)
    {
           $product_damage= ProductDamage::create([
                'date'      => Carbon::now()->format('y-m-d'),
                'amount'    => $request->prices[$key],
            ]);
            $damagedItem = ProductDamageItem::create([
                'product_damage_id' =>$product_damage->id,
                'fk_product_id' => $request->product_ids[$key],
                'description'   => $request->comment,
                'type'          => 'damaged',
                'quantity'      => $request->quantities[$key],
                'price'         => $request->prices[$key],
            ]);

            $wastage = $damagedItem->wastage()->create([
                'wastagable_type'   => ProductDamage::class,
                'wastagable_id'     => $product_damage->id,
                'quantity'          => $request->quantities[$key],
            ]);
    }


    private function makeTransaction($invoice, $request)
    {
        if ($invoice->paid_amount > 0) {
            $transaction = $invoice->transaction()->create([
                'fk_account_id' => defaultAccount()->id,
                'date'          => $invoice->date,
                'amount'        => $invoice->paid_amount
            ]);
        }
    }


    public function show(PurchaseReturn  $purchaseReturn)
    {
        return view('admin.purchase.return.show', compact('purchaseReturn'));
    }


    public function destroy($id)
    {
        try {
            \DB::transaction(function () use ($id) {
                $purchaseReturn = PurchaseReturn::find($id);
                $supplier_id = $purchaseReturn->supplier_id;

                $purchaseReturn->transaction()->delete();
          
                foreach ($purchaseReturn->purchaseReturnDetails as $key => $detail) {
                    $this->updateStock($detail);
                    $detail->delete();
                }
                $purchaseReturn->delete();

                (new PurchaseService())->updateSupplierBalance($supplier_id);
            });
            return back()->withSuccess('Purchase Return Deleted Successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->withError($ex->getMessage());
        }
    }



    private function updateStock($item)
    {
        $stock = ProductStock::companies()->where('fk_product_id', $item->product_id)->whereNull('warehouse_id')->first();

        if ($stock) {
            $stock->increment('available_quantity', $item->quantity);
            $stock->decrement('purchased_quantity', $item->quantity);
            $stock->save();
        }
    }

    public function getSupplierInvoices(Request $request)
    {

        return Purchase::where('fk_supplier_id', $request->supplier_id)->get()->map(function($purchase) {
            $data['id'] = $purchase->id;
            $data['invoice_no'] = '#P-' . str_pad($purchase->id, 5, '0', STR_PAD_LEFT);
            return $data;
        });
    }


    
    public function getSupplierPurchasedProducts(Request $request)
    {
        if($request->invoice_id != '') {

            return Product::select('id', 'product_name as name', 'product_code as code', 'product_cost as price')
            ->withCount(['purchase_details as quantity' => function($q) use($request) {
                return $q->where('fk_purchase_id', $request->invoice_id)->select(\DB::Raw('SUM(quantity)'));
            }])->whereHas('purchase_details', function($q) use($request) {
                $q->where('fk_purchase_id', $request->invoice_id);
            })->get();
        } else {
            return Product::select('id', 'product_name as name', 'product_code as code', 'product_cost as price')
            ->withCount(['purchase_details as quantity' => function($q) use($request) {
                    $q->whereHas('purchase', function($qr) use($request) {
                        $qr->where('fk_supplier_id', $request->supplier_id);
                    })->select(\DB::Raw('SUM(quantity)'));
            }])->with(['product_stock'=>function($query){
                return $query->select('available_quantity','fk_product_id');
            }])
            // comment=> purchase return relationship.
            // ->whereHas('purchase_details', function($q) use($request) {
            //     $q->whereHas('purchase', function($qr) use($request) {
            //         $qr->where('fk_supplier_id', $request->supplier_id);
            //     });
            // })
            ->get();
        }
    }
}
