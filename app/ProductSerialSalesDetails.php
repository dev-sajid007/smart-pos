<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSerialSalesDetails extends Model
{
    protected $guarded=[];

    public function sale_details()
    {
        return $this->belongsTo(SalesDetails::class, 'sales_details_id');
    }
}


