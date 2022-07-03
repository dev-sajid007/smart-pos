<?php


namespace App\Services;


use DB;
use App\Product;
use App\Purchase;
use App\Supplier;
use App\ProductStock;
use App\ProductSerial;
use App\SoftwareSetting;
use App\Services\Reports\SupplierReportServices;

class PurchaseService
{
    public $purchase;
    private $request;



    public function makeInvoice()
    {
        $this->request = $request = request();

        $this->purchase = Purchase::create([
            'purchase_date'     => $request->purchase_date,
            'fk_supplier_id'    => $request->fk_supplier_id,
            'warehouse_id'      => $request->warehouse_id,
            'account_id'        => $request->account_id,
            'invoice_discount'  => $request->invoice_discount ?? 0,
            'invoice_tax'       => $request->invoice_tax ?? 0,
            'total_payable'     => $request->total_payable ?? ($request->subtotal - $request->invoice_discount + $request->invoice_tax),
            'sub_total'         => $request->subtotal ?? 0,
            'paid_amount'       => $request->total_paid_amount ?? 0,
            'previous_due'      => $request->previous_due
        ]);
    }




    //Create items in invoice
    public function storePurchaseDetails()
    {
        $qtys = array_filter($this->request->quantities);
        $qtys += array_filter($this->request->free_quantities);

        $isUpdateProductCost = SoftwareSetting::companies()->where('title', 'Product Cost Update From Purchase')->where('options', 'yes')->first();

        foreach ($qtys as $key => $quantity) {
           $details= $this->purchase->purchase_details()->create([
                "fk_product_id"     => $this->request->product_ids[$key]??'',
                "quantity"          => $this->request->quantities[$key]??0,
                "free_quantity"     => $this->request->free_quantities[$key]??0,
                "unit_price"        => $this->request->unit_cost[$key]??0,
                "product_sub_total" => $this->request->product_sub_total[$key]??0,
                // "expire_date"       => $this->getFilteredExpireDate($this->request->expire_dates[$key]),
                "expire_date"       => '',
                'warehouse_id'      => $this->request->warehouse_id,
            ]);

            // update product cost with purchase when allow this feature from software setting
            if ($isUpdateProductCost) {
                $this->updateProductCost($this->request->product_ids[$key], $key);
            }
            if($this->request->serials[$key]!=null){
                $this->firstOrStoreProductSerial($this->request->product_ids[$key],$details->id,$this->request->serials[$key]);
            }
        }
    }

    public function approvePurchase()
    {
        return DB::transaction(function () {
            $this->stockManage();
            $this->makePayment();
            $this->purchase->approved();
        });
    }

    public function updateSupplierBalance($supplier_id)
    {
        $service    = new SupplierReportServices();

        $supplierBalances = (object)$service->getSuppliersBalances(Supplier::where('id', $supplier_id)->take(1)->get());

        $supplier = Supplier::where('id', $supplier_id)->first();

        $supplier->update(['current_balance' => $supplierBalances->sum('balance')]);
    }

    // ##################   private methods
    private function updateProductCost($productId, $key)
    {
        $product = Product::find($productId);
        $product->update([
            'product_cost' => $this->request->unit_cost[$key]
        ]);
    }


    private function stockManage()
    {
        foreach ($this->purchase->purchase_details as $item){
            $productStock   = ProductStock::where('fk_company_id', auth()->user()->fk_company_id)->where('fk_product_id', $item->fk_product_id)->where('warehouse_id', $this->request->warehouse_id);
            $stockCount     = $productStock->count();

            if($stockCount) {
                $this->stockUpdate($productStock, $item);
            } else {
                $this->stockCreate($item);
            }
        }
    }

    private function stockUpdate($productStock, $item)
    {
        $firstStock = $productStock->first();

        $firstStock->update([
            "purchased_quantity" => $firstStock->purchased_quantity + ($item->quantity + $item->free_quantity),
            "available_quantity" => $firstStock->available_quantity + ($item->quantity + $item->free_quantity),
        ]);
    }

    private function stockCreate($item)
    {
        ProductStock::create([
            'fk_product_id'         => $item->fk_product_id,
            'opening_quantity'      => 0,
            'purchased_quantity'    => $item->quantity + $item->free_quantity,
            'available_quantity'    => $item->quantity + $item->free_quantity,
            'sold_quantity'         => 0,
            'warehouse_id'          => $item->warehouse_id
        ]);
    }

    private function makePayment()
    {
        if ($this->purchase->paid_amount != 0) {
            $this->purchase->transaction()->create([
                'fk_account_id' => $this->purchase->account_id,
                'date'          => $this->purchase->purchase_date,
                'amount'        => -($this->purchase->paid_amount)
            ]);
        }
    }


    private function getFilteredExpireDate($expireDate)
    {
        return $expireDate != '' ? fdate(str_replace("/", "-", $expireDate), 'Y-m-d') : '';
    }

    private function firstOrStoreProductSerial($productId,$purchase_id,$productSerials){
            $productSerials=explode(",", str_replace("\r\n","",$productSerials) . "");
            foreach ($productSerials as $key => $item) {
                ProductSerial::firstOrCreate([
                    'serial'      => $item,
                ],
                [
                    'serial'      => $item,
                    'source'      => 'Purchase',
                    'product_id'  => $productId,
                    'purchase_id' => $purchase_id,
                    'created_by'  => auth()->id(),
                    'updated_by'  => auth()->id(),
                ]);
            }
    }
}
