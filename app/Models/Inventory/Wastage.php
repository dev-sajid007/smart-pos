<?php

namespace App\Models\Inventory;

use App\Models\RandomReturn;
use App\Models\RandomReturnItem;
use App\ProductStock;
use App\Sale;
use App\SalesDetails;
use Illuminate\Database\Eloquent\Model;

class Wastage extends Model
{
    protected $guarded = [];
    protected $table = 'wastage';

    public function wastagable()
    {
        return $this->morphTo();
    }

    public function itemable()
    {
        return $this->morphTo();
    }

    public function randomReturnItem()
    {
        return $this->belongsTo(RandomReturnItem::class, 'itemable_id')
            ->whereItemableType(RandomReturnItem::class);
    }

    public function getSubtotalAttribute()
    {
        return ($this->itemable->unit_price * $this->quantity) ?? 0;
    }

    public function replaces()
    {
        return $this->morphMany(Replace::class, 'replacable');
    }

    public function increaseWastageStock($increase = true)
    {
        $quantity = $increase ? $this->quantity : -$this->quantity;
        $stock = ProductStock::where('fk_product_id', $this->itemable->fk_product_id)->first();

        $stock->update([
            'available_quantity' => $stock->available_quantity - $quantity,
            'wastage_quantity' => $stock->wastage_quantity + $quantity
        ]);
        return $stock;
    }

    public function typeName()
    {
        switch ($this->wastagable_type){
            case ProductDamage::class:
                return 'Product Damage';
            case Sale::class:
                return 'Sale Damage';
            case RandomReturn::class:
                return 'Product Return';
        }
    }


}
