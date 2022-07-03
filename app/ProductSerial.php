<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;

class ProductSerial extends Model
{
    protected $guarded = ['id'];

    public function Product(){
        return $this->belongsTo(Product::class);
    }

    public function serial_sale_details()
    {
        return $this->hasOne(ProductSerialSalesDetails::class, 'product_serial_id');
    }

    public function sales()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
