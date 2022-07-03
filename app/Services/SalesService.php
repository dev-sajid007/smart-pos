<?php


namespace App\Services;


use App\Sale;
use App\Product;
use App\Customer;
use App\marketer;
use App\marketing;
use App\ProductStock;
use App\CustomerPricing;
use App\PurchaseDetails;
use App\SaleDistributor;
use App\SoftwareSetting;
use App\marketers_details;
use App\ProductSerial;
use App\ProductSerialSalesDetails;
use Carbon\Carbon;
use App\Services\Reports\CustomerReportServices;

class SalesService
{
    public $sale;
    private $request;


    public function createInvoice()
    {
        $this->request = request();
        $data = $this->request->only('sale_date', 'sale_reference', 'sub_total', 'total_payable', 'previous_due', 'sale_type');

        if ($this->request->total_payable != 'NaN'){
            $total_payable = $this->request->total_payable;
        } else {
            $total_payable =  $data['total_payable'] = 0;
        }

        if ($this->request->receive_amount != 'NaN') {
            $receive_amount = $this->request->receive_amount;
        } else {
            $receive_amount = $data['receive_amount'] = 0;
        }

        if ($this->request->invoice_tax != 'NaN') {
            $invoice_tax = $this->request->invoice_tax;
        } else {
            $invoice_tax = $data['invoice_tax'] = 0;
        }

        $todaySale = Sale::whereSale_date($this->request->sale_date)->latest('created_at')->first();
        if(!$todaySale)
        {
            $data['sale_invoice_id'] = Carbon::now()->format('Ydm').'1';
        }else
        {
            $data['sale_invoice_id'] = Sale::max('sale_invoice_id') + 1;
        }

        $data['fk_customer_id']     = $this->request->fk_customer_id ?? $this->request->defaultCustomer;
        $data['invoice_discount']   = ceil($this->request->invoice_discount ?? 0);
        $data['invoice_tax']        = $invoice_tax ?? 0;
        $data['cod_percent']        = $this->request->cod_percent ?? 0;
        $data['cod_amount']         = $this->request->cod_amount ?? 0;
        $data['advanced']           = $this->request->advanced_payment;
        $data['previous_due']       = $this->request->previous_due;
        $data['receive_amount']     = $this->request->receive_amount ?? 0;
        $data['balance']            = $total_payable - $receive_amount;
        $data['change_amount']      = 0;
        $data['warehouse_id']       = $this->request->warehouse_id;
        $data['marketers_id']       = $this->request->marketers_id;
        $data['point_amount']       = $this->request->customer_point_amount ?? 0;
        $data['note']               = $this->request->note ?? '0';

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

    public function storeSaleDetails()
    {
        // dd($this->request->all(),($this->request->has_serials=="1") ? true : false);
        $isUpdateCustomerSalePrice = $this->isUpdateCustomerSalePrice();

        foreach ($this->request->product_names as $product_id => $name) {
            $details=$this->sale->sale_details()->create([
                'fk_product_id'     => $product_id,
                'warehouse_id'      => $this->request->warehouse_id,
                'quantity'          => $this->request->quantities[$product_id],
                'unit_price'        => $this->request->unit_prices[$product_id],
                'product_sub_total' => ($this->request->quantities[$product_id] * $this->request->unit_prices[$product_id]),
                'product_item_ids'  => $this->request->product_item_ids[$product_id]??'',
                'product_cost'      => $this->getProductCost($product_id) ?? $this->request->unit_prices[$product_id],  // for profit report
                'product_discount'  => $this->request->product_discounts[$product_id] ?? 0,
            ]);

            // Update Product Stock
            $this->updateProductStock($product_id, $product_id);

            // update customer wise sale price
            if ($isUpdateCustomerSalePrice) {
                $this->updateCustomerSalePrice($product_id, $product_id);
            }
            if($this->request->has_serials[$product_id]=="1"){
                $this->storeSaleSerial($details->id,$this->request->product_serials[$product_id]);
            }
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
        $total_sales_amount = $this->getTotalSalesAmount();

        $total_payable = ($total_sales_amount + $this->sale->invoice_tax + ($this->request->currier_amount ?? 0) + ($this->request->cod_amount ?? 0) - $this->sale->invoice_discount);
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

    public function marketers_commission()
    {
        $request = \request();
        try {
            if($request->marketers_id != ''){
                $marketers_details = marketers_details::where('start_amount', '<=', $request->payable_amount)
                    ->where('end_amount', '>=', $request->payable_amount)->first();
                $percantage = $marketers_details->marketers_commission;
                $payable    = $request->payable_amount;
                $marketers_amount = round(($payable * $percantage) / 100);
                $marketers = marketer::where('id', $request->marketers_id);
                $balance = $marketers->first()->balance;
                $new_amount = $balance + $marketers_amount;
                $marketers->update([
                    'balance' => $new_amount
                ]);
            }
        } catch (\Exception $ex) {
            return;
            // dd($ex->getMessage());
        }
    }

    private function getReceiverId(){
        return SaleDistributor::create([
            'name' => $this->request->receiver,
            'type' => 'receiver'
        ])->id ?? 1;
    }

    private function getDelivererId(){
        return SaleDistributor::create([
            'name' => $this->request->deliverer,
            'type' => 'deliverer'
        ])->id ?? 1;
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
    private function updateProductStock($productId, $key)
    {
        $productStock = ProductStock::where('fk_product_id', $productId)->where('fk_company_id', auth()->user()->fk_company_id)->where('warehouse_id', $this->request->warehouse_id)->first();


        if ($productStock) {
            $productStock->update([
                'sold_quantity'     => ($productStock->sold_quantity + $this->request->quantities[$key]),
                'available_quantity'=> ($productStock->available_quantity - $this->request->quantities[$key]),
            ]);

        } else {

            ProductStock::create([
                'fk_product_id'         => $productId,
                'opening_quantity'      => 0,
                'available_quantity'    => (0 - $this->request->quantities[$key]),
                'sold_quantity'         => $this->request->quantities[$key],
                'warehouse_id'          => $this->request->warehouse_id
            ]);
        }
    }

    private function updateCustomerSalePrice($product_id, $key)
    {
        CustomerPricing::updateOrCreate([
            'customer_id' => $this->request->fk_customer_id ?? $this->request->defaultCustomer,
            'product_id'  => $product_id,
        ], ['price'       => $this->request->unit_prices[$key] ]);
    }

    private function getTotalSalesAmount()
    {
        return $this->sale->sale_details->sum(function ($item) {
            return ($item->quantity * $item->unit_price);
        });
    }
    private function storeSaleSerial($sale_detail_id,$serials){
        foreach($serials as $key=>$serial){
            ProductSerial::where('serial',$serial)->update([
                'is_sold'   =>1,
                'sale_id'   =>$this->sale->id
            ]);
            ProductSerialSalesDetails::create([
                'sales_details_id'=>$sale_detail_id,
                'product_serial_id'=>ProductSerial::where('serial',$serial)->first()->id,
            ]);
        }
    }
}
