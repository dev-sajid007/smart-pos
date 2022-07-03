<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductRak extends Model
{
    protected $table = 'product_raks';

    protected $guarded = ['id'];

    public function rak()
    {
        return $this->belongsTo(Rak::class, 'rak_id', 'id')->select('name', 'id');
    }
}
