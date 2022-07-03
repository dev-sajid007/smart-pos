<?php

namespace App\Http\Requests;

use App\Sale;
use App\Balance;
use App\Customer;
use App\marketer;
use App\marketing;
use App\Transaction;
use App\ProductStock;
use App\SalesDetails;
use App\ProductSerial;
use App\SaleDistributor;
use App\SoftwareSetting;
use App\marketers_details;
use App\Services\SalesService;
use App\ProductSerialSalesDetails;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SaleController;
use Illuminate\Foundation\Http\FormRequest;

class SaleUpdateRequest extends FormRequest
{
    private $sale;
    private $old_customer_id;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_ids.*'     => 'required|not_in:0',
            'unit_prices.*'     => 'required',
            'fk_customer_id'    => 'required',
            'quantities.*'      => 'required',
            'invoice_total'     => 'required',
        ];
    }

    public function messages()
    {
        return [
            'product_ids.*.required'    => 'The selected product is invalid',
            'product_ids.*.not_in'      => 'The selected product is invalid',
            'unit_prices.*.required'    => 'The unit price is required',
            'quantities.*.required'     => 'The quantity field is required',
            'quantities.*.lte'          => 'Stock Unavailable!',
        ];
    }

    // request update method
    public function updateSale($sale)
    {
        $this->sale = $sale;
        $this->old_customer_id = $sale->fk_customer_id;

        DB::transaction(function () {

            $sale = $this->sale;
            $new_paid_amount = $this->receive_amount - $sale->paid_amount;

            $sub_total = Sale::where('id', $sale->id)->first()->sub_total;
            if($this->payable_amount != $sub_total){
                $marketers_details = marketers_details::where('start_amount', '<=', $sub_total)
                                     ->where('end_amount', '>=', $sub_total)->first();
                $percantage = $marketers_details->marketers_commission;
                $marketers_amount = round(($sub_total * $percantage) / 100);
                $marketers = marketer::where('id', $this->marketers_id)->first();
                $balance = $marketers->balance ?? 0;
                $old_amount = $balance - $marketers_amount;
                $this->marketers_commission($old_amount);
            }

            $this->updateInvoice($sale->id);

            $this->saleMeta($sale);

            $this->updateItems($sale);

            $this->payment($sale, $this->account_information);

            // update customer balance\
            (new SalesService())->updateCustomerBalance($sale->fk_customer_id);

            // Edit marketers Commission

        });
    }

    private function updateInvoice($sale_id)
    {
        $data = $this->only('sale_date', 'sale_reference', 'sub_total', 'total_payable');

        $data['fk_customer_id']     = $this->fk_customer_id ?? $this->defaultCustomer;
        $data['invoice_discount']   = ceil($this->invoice_discount ?? 0);
        $data['invoice_tax']        = $this->invoice_tax ?? 0;
        $data['previous_due']       = $this->sale->fk_customer_id == $this->fk_customer_id ? $this->sale->previous_due :  $this->request->previous_due;
        $data['advanced']           = $this->advanced_payment;
        $data['balance']            = $this->total_payable - ($this->receive_amount ?? 0);

        $data['receive_amount']     = $this->receive_amount ?? 0;
        $data['cod_percent']        = $this->cod_percent ?? 0;
        $data['cod_amount']         = $this->cod_amount ?? 0;
        $data['paid_amount']        = $this->receive_amount ?? 0;
        $data['point_amount']       = $this->customer_point_amount ?? 0;
        $data['marketers_id']       = $this->marketers_id;
        $data['note']               = $this->note?? '0';

        $this->sale->update($data);
        $this->updateCurrier();
    }


    private function updateCurrier()
    {
        if ($this->currier_amount != '') {
            $this->sale->update([
                'currier_id'        => $this->currier_id,
                'currier_amount'    => $this->currier_amount
            ]);
        }
    }

    public function saleMeta(Sale $sale)
    {
        if ($this->received_by == '' && $this->delivered_by == '') {
            try {
                $sale->saleMeta()->delete();
            } catch (\Exception $ex) {}
        } else {
            $sale->saleMeta()->update([
                'received_by'   => $this->received_by ? $this->received_by : $this->getReceivedBy(),
                'delivered_by'  => $this->delivered_by ? $this->delivered_by : $this->getDeliveredBy()
            ]);
        }
    }

    private function getReceivedBy()
    {
        if (ltrim($this->receiver)) {
            return SaleDistributor::create([
                'name' => $this->receiver,
                'type' => 'receiver'
            ])->id;
        }
    }

    private function getDeliveredBy()
    {
        if (ltrim($this->deliverer)) {
            return SaleDistributor::create([
                'name' => $this->deliverer,
                'type' => 'deliverer'
            ])->id;
        }
    }



    private function updateItems($sale)
    {
        $settings = SoftwareSetting::where('title', 'Need Additional Item Id Field')->first();
        $has_item_id_fields = optional($settings)->options ==  'need-additional-item-id-field';

        // delete unmatched products
        foreach ($sale->sale_details as $k => $detail) {

            if (!in_array($detail->fk_product_id, $this->product_ids)) {
                $this->decreaseStock($detail);
                $detail->delete();
            }
        }
        $existing_product_ids = $sale->sale_details->pluck('fk_product_id')->toArray() ?? [];


        foreach ($this->product_ids as $key => $product_id) {
            $product_sub_total = ($this->quantities[$key] ?? 0) * ($this->unit_prices[$key] ?? 0);

            // if product exist then update this product
            if (in_array($product_id, $existing_product_ids)) {
                $details = $sale->sale_details->where('fk_product_id', $product_id)->first();

                $this->updateStock($product_id, $key, $details);

                $details->update([
                    'quantity'          => $this->quantities[$key],
                    'unit_price'        => $this->unit_prices[$key],
                    'product_sub_total' => $product_sub_total,

                    'product_item_ids'  => $has_item_id_fields ? $this->product_item_ids[$key] : $details->product_item_ids,

                ]);
            } else {
                $details =  $sale->sale_details()->create([
                    'fk_product_id'     => $product_id,
                    'quantity'          => $this->quantities[$key],
                    'unit_price'        => $this->unit_prices[$key],
                    'warehouse_id'      => $this->warehouse_id,
                    'product_sub_total' => $product_sub_total,
                    'product_item_ids'  => $has_item_id_fields ? $this->product_item_ids[$key] : '',
                ]);
                // $this->UpdateOrStoreSaleSerial($details->id,'');
                $this->addStock($product_id, $key);
            }
        }
    }


    // Definition Update Product Stock
    private function addStock($productId, $key)
    {
        $productStock = ProductStock::where('fk_product_id', $productId)->where('warehouse_id', $this->warehouse_id)->first();
        $productStock->update([
            'sold_quantity'     => $productStock->sold_quantity += $this->quantities[$key],
            'available_quantity'=> $productStock->available_quantity -= $this->quantities[$key],
        ]);
    }


    // Definition Update Product Stock
    private function updateStock($productId, $key, $detail)
    {
        $productStock = ProductStock::where('fk_product_id', $productId)->where('warehouse_id', $this->warehouse_id)->first();

        $productStock->update([
            'sold_quantity'     => $productStock->sold_quantity - $detail->quantity + $this->quantities[$key],
            'available_quantity'=> $productStock->available_quantity + $detail->quantity - $this->quantities[$key],
        ]);
    }

    private function decreaseStock($detail)
    {
        $productStock = ProductStock::where('fk_product_id', $detail->fk_product_id)->where('warehouse_id', $this->warehouse_id)->first();
        $sold_quantity = $productStock->sold_quantity;
        $available_quantity = $productStock->available_quantity;


        if ($productStock) {
            $productStock->update([
                'sold_quantity'     => $sold_quantity - $detail->quantity,
                'available_quantity'=> $available_quantity + $detail->quantity,
            ]);
        }
    }

    private function payment($sale, $account_id = null)
    {
        if ($this->receive_amount != '') {
            $transaction = Transaction::where('transactionable_type', 'App\Sale')->where('transactionable_id', $sale->id)->first();
            if ($transaction) {
                $transaction->update([
                    'fk_account_id' => $account_id ?? defaultAccount()->id,
                    'date'          => $sale->sale_date,
                    'amount'        => $this->receive_amount ?? 0
                ]);
            } else {
                $transaction = $sale->transaction()->create([
                    'fk_account_id' => $account_id ?? defaultAccount()->id,
                    'date'          => $sale->sale_date,
                    'amount'        => $this->receive_amount ?? 0
                ]);
            }
        }
    }
    public function marketers_commission($old_amount)
    {
        try {
            $marketers_details = marketers_details::where('start_amount', '<=', $this->payable_amount)
                ->where('end_amount', '>=', $this->payable_amount)->first();
            $percantage = $marketers_details->marketers_commission;
            $payable    = $this->payable_amount;
            $marketers_amount = round(($payable * $percantage) / 100);
            $marketers = marketer::where('id', $this->marketers_id);
            // $balance = $marketers->first()->balance;
            $balance = $old_amount;
            $new_amount = $balance + $marketers_amount;
            $marketers->update([
                'balance' => $new_amount
            ]);
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }

    }
    private function UpdateOrStoreSaleSerial($sale_detail_id,$serials){
        foreach($serials as $key=>$serial){
           $serial= ProductSerial::where('serial',$serial)->first();
           $serial->update(['is_sold'=>1]);
            ProductSerialSalesDetails::firstOrCreate([
                'sales_details_id'=>$sale_detail_id,
                'product_serial_id'=>$serial->id,
            ],
            [
                'sales_details_id'=>$sale_detail_id,
                'product_serial_id'=>$serial->id,
            ]);
        }
    }
}
