<?php


namespace App\Services\StockTransfer;


use App\Product;
use App\ProductStock;
use App\StockTransfer;
use Illuminate\Support\Facades\DB;

class CompanyToCompanyStockTransferService
{
    public $stockTransfer;
    private $request;

    public function storeStockTransfer()
    {
        $this->request = $request = request();

        $this->stockTransfer = StockTransfer::create([
            'invoice_no'        => 'str-' . ((StockTransfer::max('id') ?? 1) + 10000),
            'remarks'           => $request->remarks,
            'date'              => $request->date ?? fdate(now(), 'Y-m-d'),
            'to_company_id'     => $request->company_id,
            'from_warehouse_id' => $request->warehouse_id,
            'total_quantity'    => array_sum($request->quantities),
        ]);
    }

    public function storeStockTransferDetails()
    {
        foreach ($this->request->product_ids as $key => $product_id) {
            $this->stockTransfer->stock_transfer_details()->create([
               'product_id' => $product_id,
               'quantity'   => $this->request->quantities[$key],
               'unit_price' => $this->getProductPrice($product_id)
            ]);

            $this->reduceProductStock($product_id, $this->request->quantities[$key], $this->request->to_warehouse_id);
        }
    }

    public function receiveStock($id)
    {
        $this->stockTransfer = StockTransfer::find($id);

        foreach ($this->stockTransfer->stock_transfer_details as $key => $details) {
            $this->increaseProductStock($details->product_id, $details->quantity, request('warehouse_id'));
        }

        $this->stockTransfer->update([
            'is_received' => 1,
            'to_warehouse_id' => request('warehouse_id')
        ]);
    }

    private function getProductPrice($product_id)
    {
        return optional(Product::find($product_id))->product_cost ?? 0;
    }

    private function reduceProductStock($product_id, $quantity)
    {
        $product_stock = ProductStock::companies()->where('fk_product_id', $product_id)->where('warehouse_id', $this->request->from_warehouse_id)->first();

        if ($product_stock) {
            $product_stock->increment('transfer_out', $quantity);
            $product_stock->decrement('available_quantity', $quantity);
            $product_stock->save();
        } else {
            $openingQuantity = optional(Product::find($product_id))->opening_quantity ?? 0;
            ProductStock::create([
                'warehouse_id'          => $this->request->from_warehouse_id,
                'fk_product_id'         => $product_id,
                'transfer_out'          => $quantity,
                'opening_quantity'      => $openingQuantity,
                'available_quantity'    => $openingQuantity - $quantity
            ]);
        }
    }

    private function increaseProductStock($product_id, $quantity, $warehouse_id = null)
    {
        $product_stock = ProductStock::companies()->where('fk_product_id', $product_id)->where('warehouse_id', $warehouse_id)->first();

        if ($product_stock) {
            $product_stock->increment('transfer_in', $quantity);
            $product_stock->increment('available_quantity', $quantity);
            $product_stock->save();
        } else {
            $product_stocks = ProductStock::create([
                'warehouse_id'          => $warehouse_id,
                'fk_product_id'         => $product_id,
                'transfer_in'           => $quantity,
                'available_quantity'    => $quantity
            ]);
        }
    }

    public function deleteStockTransfer($id)
    {
        DB::transaction(function () use ($id) {
            $stock = StockTransfer::find($id);
            foreach ($stock->stock_transfer_details as $key => $detail) {
                $product_stock_out = ProductStock::where('fk_company_id', $stock->from_company_id)->where('fk_product_id', $detail->product_id)->where('warehouse_id', $stock->from_warehouse_id)->first();


                if ($product_stock_out) {
                    $product_stock_out->decrement('transfer_out', $detail->quantity);
                    $product_stock_out->increment('available_quantity', $detail->quantity);
                    $product_stock_out->save();
                }

                if ($stock->is_received == 1) {
                    $product_stock_in = ProductStock::where('fk_company_id', $stock->to_company_id)->where('fk_product_id', $detail->product_id)->where('warehouse_id', $stock->to_warehouse_id)->first();

                    if ($product_stock_in) {
                        $product_stock_in->decrement('transfer_in', $detail->quantity);
                        $product_stock_in->decrement('available_quantity', $detail->quantity);
                        $product_stock_in->save();
                    }
                }
            }

            $stock->delete();
        });
    }
}
