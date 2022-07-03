<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerPricing extends Model
{
    protected $guarded = ['id'];
    protected $table = 'customers_products';
}
