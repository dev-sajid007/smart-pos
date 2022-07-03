<?php


namespace App\Services;

use App\Customer;
use App\CustomerPricing;
use App\Product;
use App\ProductPackage;
use App\ProductStock;
use App\PurchaseDetails;
use App\Sale;
use App\SaleDistributor;
use App\Services\Reports\CustomerReportServices;
use App\SoftwareSetting;
use DB;

class PackageSaleService
{
    private $request;
    private $totalSaleAmount = 0;
    public $sale;

    public function storeSale($request)
    {
        $this->request = $request;


        DB::transaction(function () {

            // store sale on sales table
            $this->createInvoice();


            // add currier for sale
            $this->addSaleCurrier();


            // add meta sale where include receiver info
            $this->addMetaSale();

            // store product items to sales_details table
            $this->storeSaleDetails();


            // store customer payment
            $this->makeCustomerPayment();


            // update total sales amount
            $this->updateSalesTotalAmount();

            
            // update customer balance
            $this->updateCustomerBalance($this->request->fk_customer_id);
           
        });
    }


    private function createInvoice()
    {
        $this->request = request();

        $data = $this->request->only('sale_date', 'sale_reference', 'sub_total', 'total_payable', 'previous_due', 'sale_type');

        $data['fk_customer_id']     = $this->request->fk_customer_id;
        $data['invoice_discount']   = $this->request->invoice_discount ?? 0;
        $data['invoice_tax']        = $this->request->invoice_tax ?? 0;
        $data['advanced']           = $this->request->advanced_payment;
        $data['previous_due']       = $this->request->previous_due;
        $data['receive_amount']     = $this->request->receive_amount ?? 0;
        $data['balance']            = (($this->request->total_payable ?? 0) - ($this->request->receive_amount ?? 0));
        $data['change_amount']      = 0;
        $data['warehouse_id']       = $this->request->warehouse_id;
        $data['point_amount']       = $this->request->customer_point_amount ?? 0;
        $data['sale_type']          = 'Package';


        $this->sale = Sale::create($data);
    }



    public function addSaleCurrier()
    {
        if ($this->request->currier_amount != '') {
            $this->sale->update([
                'currier_id'        => $this->request->currier_id,
                'currier_amount'    => $this->request->currier_amount
            ]);
        }
    }

    public function addMetaSale()
    {

        if (ltrim($this->request->receiver) || ltrim($this->request->deliverer)) {
            $this->sale->saleMeta()->create([
                'received_by'   => $this->request->received_by  ? $this->request->received_by   : $this->getReceiverId(),
                'delivered_by'  => $this->request->delivered_by ? $this->request->delivered_by  : $this->getDelivererId()
            ]);
        }
    }


    private function getReceiverId()
    {
        return SaleDistributor::create([
            'name' => $this->receiver,
            'type' => 'receiver'
        ])->id ?? 1;
    }

    private function getDelivererId()
    {
        return SaleDistributor::create([
            'name' => $this->deliverer,
            'type' => 'deliverer'
        ])->id ?? 1;
    }

    public function storeSaleDetails()
    {
        $isUpdateCustomerSalePrice = $this->isUpdateCustomerSalePrice();

        foreach ($this->request->package_ids as $key => $package_id) {
            $package = ProductPackage::with('package_details')->find($package_id);
            $this->totalSaleAmount += ($this->request->prices[$key] * $this->request->quantities[$key]);

            foreach($package->package_details as $pk => $detail) {
                
                $sale_details = $this->sale->sale_details()->create([
                    'fk_product_id'     => $detail->product_id,
                    'package_id'        => $package_id,
                    'warehouse_id'      => $this->request->warehouse_id,
                    'quantity'          => $quantity = ($this->request->quantities[$key] * $detail->quantity),
                    'unit_price'        => $detail->price,
                    'package_price'     => $this->request->prices[$key],
                    'package_qty'       => $this->request->quantities[$key],
                    'product_sub_total' => $detail->amount,
                    'product_cost'      => $this->getProductCost($detail->product_id) ?? $$detail->price,  // for profit report
                    'product_discount'  => 0,
                ]);

                // Update Product Stock
                $this->updateProductStock($detail->product_id, $quantity);
            }
            
        }
    }

    private function isUpdateCustomerSalePrice()
    {
        return optional(SoftwareSetting::companies()->where('title', 'Seller Sale Price Update From Sale')->where('options', 'yes')->first())->options ? true : false;
    }



    private function getProductCost($product_id)
    {
        $purchase = PurchaseDetails::latest()->where('fk_product_id', $product_id)->first();
        if ($purchase) {
            return $purchase->unit_price;
        }
        return optional(Product::find($product_id))->product_cost;
    }





    // Definition Update Product Stock
    private function updateProductStock($productId, $quantity)
    {
        $productStock = ProductStock::where('fk_product_id', $productId)->where('fk_company_id', auth()->user()->fk_company_id)->where('warehouse_id', $this->request->warehouse_id)->first();


        if ($productStock) {
            $productStock->update([
                'sold_quantity'     => ($productStock->sold_quantity + $quantity),
                'available_quantity'=> ($productStock->available_quantity - $quantity),
            ]);
            
        } else {

            ProductStock::create([
                'fk_product_id'         => $productId,
                'opening_quantity'      => 0,
                'available_quantity'    => (0 - $quantity),
                'sold_quantity'         => $quantity,
                'warehouse_id'          => $this->request->warehouse_id
            ]);
        }
    }




    public function makeCustomerPayment()
    {
        $amount = $this->request->change_amount
            ? $this->request->totalAftertax_temp
            : $this->request->receive_amount;

        if ($amount > 0) {
            $transaction = $this->sale->transaction()->create([
                'fk_account_id' => $this->request->account_id ?? defaultAccount()->id,
                'date'          => $this->sale->sale_date,
                'amount'        => $amount
            ]);
        }
    }



    public function updateSalesTotalAmount()
    {
        $total_sales_amount = $this->totalSaleAmount;
        $total_payable = ($total_sales_amount + $this->sale->invoice_tax + ($this->request->currier_amount ?? 0) - $this->sale->invoice_discount);
        $balance = $this->request->previous_due - $this->request->advanced_payment + $total_payable - $this->sale->receive_amount;

        $this->sale->update([
           'sub_total'      => $total_sales_amount,
           'total_payable'  => $total_payable,
           'paid_amount'    => $this->sale->receive_amount,
           'receive_amount' => $this->sale->receive_amount,
           'balance'        => $balance
        ]);
    }


    public function updateCustomerBalance($customer_id)
    {
        $service    = new CustomerReportServices();

        $customerBalances = (object)$service->getCustomersBalances(Customer::where('id', $customer_id)->take(1)->get());
        $customer = Customer::find($customer_id);
        $customer->update(['current_balance' => $customerBalances->sum('balance')]);
    }
}