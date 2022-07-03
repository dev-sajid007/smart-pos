<?php

namespace App\Models;

use App\Models\Inventory\InventoryReturn;
use App\Models\Inventory\Wastage;
use App\Product;
use Illuminate\Database\Eloquent\Model;

class RandomReturnItem extends Model
{
    protected $guarded = [];

    public function randomReturn()
    {
        return $this->belongsTo(RandomReturn::class, 'random_return_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function returns()
    {
        return $this->morphOne(InventoryReturn::class, 'itemable');
    }

    public function wastages()
    {
        return $this->morphOne(Wastage::class, 'itemable');
    }
}
