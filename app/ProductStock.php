<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductStock extends Model
{

    use AddingCreatedBy;
    protected $guarded = ['id'];


    public function stock_product()
    {
        return $this->belongsTo('App\Product', 'fk_product_id');
    }

    public function stock_purchase_details()
    {
        return $this->hasMany('App\PurchaseDetails', 'fk_product_id', 'fk_product_id');
    }


    public function product_alert_quantity()
    {
         return $this->belongsTo('App\Product', 'fk_product_id', 'id')
             ->where('products.product_alert_quantity', '>=', 'product_stocks.available_quantity');
    }

    public function product()
    {
        return $this->hasOne(Product::class,'id', 'fk_product_id')
            ->select('id','product_name','fk_category_id','supplier_id');
    }

    public function scopeCompanies($query)
    {
        return $query->where('fk_company_id', auth()->user()->fk_company_id);
    }


    public function purchase_details()
    {
        return $this->hasMany(PurchaseDetails::class, 'fk_product_id', 'fk_product_id');
    }


}
