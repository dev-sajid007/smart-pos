<?php

namespace App\Models\Inventory;

use App\Product;
use App\ProductStock;
use Illuminate\Database\Eloquent\Model;

class Replace extends Model
{
    protected $guarded = [];

    public function replacable()
    {
        return $this->morphTo();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inventoryReturn()
    {
        return $this->belongsTo(InventoryReturn::class);
    }

    public function price()
    {
        return number_format(($this->amount / $this->quantity), 2);
    }

    public function revertStock($flip = false)
    {
        $quantity = $flip ? $this->quantity : -$this->quantity;

        $stock = ProductStock::where('fk_product_id', $this->product_id)->first();

        $stock->update([
            'available_quantity' => $this->product->product_stock->available_quantity - $quantity,
            'sold_quantity' => $this->product->product_stock->sold_quantity + $quantity,
        ]);
    }



}
