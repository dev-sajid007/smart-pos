<?php

namespace App\Models\Inventory;

use App\Product;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\ProductDamage;

class ProductDamageItem extends Model
{
    protected $guarded = [''];

    public function wastage()
    {
        return $this->morphOne(Wastage::class, 'itemable');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'fk_product_id');
    }

    public function product_damage()
    {
        return $this->belongsTo(ProductDamage::class);
    }
}
