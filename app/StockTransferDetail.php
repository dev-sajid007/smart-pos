<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockTransferDetail extends Model
{
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id')->with('product_unit');
    }
}
