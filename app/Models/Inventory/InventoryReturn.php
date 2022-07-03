<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class InventoryReturn extends Model
{
    protected $guarded = [];

    public function returnable()
    {
        return $this->morphTo();
    }

    public function itemable()
    {
        return $this->morphTo();
    }

    public function replaces()
    {
        return $this->morphMany(Replace::class, 'replacable');
    }

    public function sale()
    {
        return $this->itemable()->sale();
    }

    public function returnSubtotal()
    {
        return $this->itemable->unit_price * $this->quantity;
    }
    public function getSubtotalAttribute()
    {
        return $this->returnSubtotal() ?? 0;
    }



}
