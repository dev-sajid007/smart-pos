<?php

namespace App;

use App\Models\Inventory\InventoryReturn;
use App\Models\Inventory\Wastage;
use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;

class SalesDetails extends Model
{

    use AddingCreatedBy;
    protected $guarded = ['id'];

    public function sale()
    {
        return $this->belongsTo('App\Sale', 'fk_sales_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'fk_product_id');
    }


    public function package()
    {
        return $this->belongsTo(ProductPackage::class, 'package_id');
    }

    public function itemReturn()
    {
        return $this->morphMany(InventoryReturn::class, 'itemable');
    }
    public function itemWasted()
    {
        return $this->morphMany(Wastage::class, 'itemable');
    }

    public function getReturnAmountAttribute()
    {
        return $this->itemReturn->returnable->unit_price ?? 0;
    }
    public function getWastedAmountAttribute()
    {
        return $this->itemWasted->wastagable->unit_price ?? 0;
    }

    public function getTotalReturnAmountAttribute()
    {
        return $this->returnAmount + $this->wastedAmount;
    }

    public function returnableQuantity()
    {
        if($this->itemReturn->isNotEmpty()){
            return $this->quantity - $this->itemReturn->sum('quantity');
        }
        if ($this->itemWasted->isNotEmpty()){
            return $this->quantity - $this->itemWasted->sum('quantity');
        }
        return $this->quantity;
    }

    public function getReturnableItemAmountAttribute()
    {
        return $this->returnableQuantity() * $this->unit_price;
    }

    public function updateStock($quantity, $wastage)
    {
        if (!$wastage) {
            $data['available_quantity'] = $this->product->product_stock->available_quantity + $quantity;
        }
        $data['sold_quantity'] = $this->product->product_stock->sold_quantity - $quantity;

        return $this->product->product_stock->update($data);
    }

    public function serials()
    {
        return $this->belongsToMany(ProductSerial::class);
    }
    
    public function sales_serials(){
        return $this->belongsTo(ProductSerialSalesDetails::class);
    }
}
