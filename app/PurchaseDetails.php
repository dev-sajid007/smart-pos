<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetails extends Model
{
    use AddingCreatedBy;

    protected $guarded = ['id'];

    public function purchase()
    {
        return $this->belongsTo('App\Purchase', 'fk_purchase_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'fk_product_id');
    }

    public function product_stock()
    {
        return $this->belongsTo('App\ProductStock', 'fk_product_id', 'fk_product_id');
    }

    public function sales_details()
    {
        return $this->hasOne('App\SalesDetails', 'fk_product_id', 'fk_product_id')
        ->selectRaw('fk_product_id, sum(quantity) as total_sold')
        ->groupBy('fk_product_id');
    }


    public function serials()
    {
        return $this->hasMany(ProductSerial::class, 'purchase_id', 'id');
    }

    public function scopeCompanies($query)
    {
        return $query->where('fk_company_id', auth()->user()->fk_company_id);
    }


}
