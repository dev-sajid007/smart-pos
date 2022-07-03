<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Services\PurchaseService;
use Illuminate\Support\Facades\DB;

use App\{ AccountReview, PurchaseReturn, Purchase, Warehouse, Supplier, Product, ProductStock, Status, Transaction, Account, ProductSerial};

class PurchaseController extends Controller
{
    // use CurrentBalance;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    $data['purchases'] = Purchase::withCount(['purchase_details as purchase_qty'=>function($query){
        $query->select(\DB::raw("SUM(quantity)"));
       }])->companies()->userLog()->latest()->totalAmount()->paginate(30);
        return view('admin.purchase.purchases.index', $data);
    }

    public function create()
    {
        $data['account_infos']      = Account::getAccountWithBalance();
        $data['pendingStatusId']    = Status::where('name', 'Pending')->first()->id;
        $data['suppliers']          = Supplier::companies()->get();
        $data['products']           = Product::get();
        $data['warehouses']         = Warehouse::companies()->pluck('name', 'id');
        $data['currentBalance']     = 0;

        return view('admin.purchase.purchases.supplier-wise-purchase-create', $data);
    }

    public function individualPurchase()
    {
        $data['account_infos']      = Account::getAccountWithBalance();
        $data['pendingStatusId']    = Status::where('name', 'Pending')->first()->id;
        $data['suppliers']          = Supplier::companies()->pluck('name', 'id');
        $data['warehouses']         = Warehouse::companies()->pluck('name', 'id');
        $data['products']           = Product::select('id', 'product_code', 'product_name', 'product_cost', 'has_serial')->get();
        $data['currentBalance']     = 0;

        return view('admin.purchase.purchases.individual-purchase', $data);
    }



    public function store(PurchaseRequest $request)
    {

        try {
            $purchase = $request->storePurchase();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        return redirect()->route('purchases.show', $purchase->id)->withSuccess("Purchase Created Successfully!");
    }

    public function approve(PurchaseRequest $request, Purchase $purchase)
    {
        $request->approve($purchase);
        (new PurchaseService())->updateSupplierBalance($purchase->fk_supplier_id);
        return back()->withSuccess('Purchase Approved Successfully!');
    }



    public function show($id)
    {
        $purchase =  Purchase::with('purchase_supplier')->find($id);
        return view('admin.purchase.purchases.show', compact('purchase'));
    }



    public function destroy(Purchase $purchase)
    {
        $supplier_id = $purchase->fk_supplier_id;

        $status = DB::transaction(function ()use($purchase) {
            $errCount = 0;


            if ($purchase->isApproved()) {
                $purchase_id = $purchase->id;
                ProductSerial::where('purchase_id', $purchase_id)->delete();

                foreach ($purchase->purchase_details as $item) {

                    if ($item->product->has_serial == 1) {
                        if($serial=ProductSerial::where('purchase_id', $item->id)){
                            $serial->delete();
                        }
                    }
                    $productStock = ProductStock::companies()->where('fk_product_id', $item->fk_product_id)->where('warehouse_id', $item->warehouse_id)->first();

                    if ($productStock) {
                        if ($productStock->available_quantity >= $item->quantity) {
                            $productStock->update([
                                'purchased_quantity' => $productStock->purchased_quantity -= $item->quantity,
                                'available_quantity' => $productStock->available_quantity -= $item->quantity,
                            ]);
                        } else {
                            $errCount += 1;
                        }
                    }
                }
            }


            if ($errCount > 0) {
                return ['error' => 'Purchase Can not be deleted!'];
            }

            $purchase->delete();
            (new PurchaseService())->updateSupplierBalance($purchase->fk_supplier_id);

            return ['success' => 'Purchase Deleted Successfully!'];
        });

        return redirect('purchases')->with($status);
    }





    public function get_supplier_product($id)
    {
        $supplier_products = Product::companies()->where('supplier_id', $id)->get();

        if ($supplier_products->isNotEmpty()) {
            return view('admin.purchases.products', [
                'supplier_products' => $supplier_products
            ]);
        }
        return [];
    }

    public function getSupplierBalance ($supplier_id)
    {
        $supplier = Supplier::find($supplier_id);

        $data['previous_due']       = $supplier->current_balance > 0 ? $supplier->current_balance : 0;
        $data['advanced_payment']   = $supplier->current_balance < 0 ? -$supplier->current_balance : 0;

        return response()->json($data);
    }


    public function getSupplierTransactions($supplier_id)
    {
        $transactions = Transaction::companies()->whereHasMorph('transactionable', AccountReview::class, function ($q) use ($supplier_id) {
            $q->where('transactionable_type', Supplier::class)
                ->where('transactionable_id', $supplier_id);
        })->sum('amount');

        return abs($transactions);
    }

    public function getPurchaseReturns($supplier_id)
    {
        $purchaseReturns = Transaction::companies()->whereHasMorph('transactionable', PurchaseReturn::class, function ($q) use ($supplier_id) {
            $q->where('supplier_id', $supplier_id);
        })->sum('amount');

        return abs($purchaseReturns);
    }


    public function getPurchases($supplier_id)
    {
        return $purchases = Purchase::companies()->where('fk_supplier_id', $supplier_id)
            ->withCount(['purchase_details AS total_amount' => function ($query) {
                $query->select(\DB::raw("SUM(quantity * unit_price) as total"));
            }])->get()->sum(function ($item) {
                return $item->total_amount - ($item->invoice_discount + $item->paid_amount);
            });
    }
}
