<?php


namespace App\Services\StockTransfer;


use App\Product;
use App\ProductStock;
use App\StockTransfer;
use Illuminate\Support\Facades\DB;

class WarehouseToWarehouseStockTransferService
{
    public $stockTransfer;
    private $request;

    public function storeWarehouseToWarehouseStock()
    {
        $this->request = $request = request();

        $this->stockTransfer = StockTransfer::create([
            'invoice_no'        => 'str-' . ((StockTransfer::max('id') ?? 1) + 10000),
            'remarks'           => $request->remarks,
            'date'              => $request->date ?? fdate(now(), 'Y-m-d'),
            'to_company_id'     => auth()->user()->fk_company_id,
            'from_warehouse_id' => $request->from_warehouse_id,
            'to_warehouse_id'   => $request->to_warehouse_id,
            'total_quantity'    => array_sum($request->quantities),
        ]);
    }

    public function storeWarehouseToWarehouseStockDetails()
    {
        foreach ($this->request->product_ids as $key => $product_id) {
            $this->stockTransfer->stock_transfer_details()->create([
               'product_id' => $product_id,
               'quantity' => $this->request->quantities[$key],
               'unit_price' => $this->getProductPrice($product_id)
            ]);

            $this->reduceProductStock($product_id, $this->request->quantities[$key]);
            $this->increaseProductStock($product_id, $this->request->quantities[$key]);
        }
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

    private function increaseProductStock($product_id, $quantity)
    {
        $product_stock = ProductStock::companies()->where('fk_product_id', $product_id)->where('warehouse_id', $this->request->to_warehouse_id)->first();

        if ($product_stock) {
            $product_stock->increment('transfer_in', $quantity);
            $product_stock->increment('available_quantity', $quantity);
            $product_stock->save();
        } else {
            $product_stocks = ProductStock::create([
                'warehouse_id'          => $this->request->to_warehouse_id,
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
                $product_stock_out = ProductStock::companies()->where('fk_product_id', $detail->product_id)->where('warehouse_id', $stock->from_warehouse_id)->first();

                if ($product_stock_out) {
                    $product_stock_out->decrement('transfer_out', $detail->quantity);
                    $product_stock_out->increment('available_quantity', $detail->quantity);
                    $product_stock_out->save();
                }

                $product_stock_in = ProductStock::companies()->where('fk_product_id', $detail->product_id)->where('warehouse_id', $stock->to_warehouse_id)->first();

                if ($product_stock_in) {
                    $product_stock_in->decrement('transfer_in', $detail->quantity);
                    $product_stock_in->decrement('available_quantity', $detail->quantity);
                    $product_stock_in->save();
                }
            }
            $stock->delete();
        });
    }
}
