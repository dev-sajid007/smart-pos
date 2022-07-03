<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleMeta extends Model
{
    protected $fillable = [
        'received_by', 'delivered_by'
    ];
    public function received()
    {
        return $this->belongsTo(SaleDistributor::class, 'received_by', 'id');
    }
    public function delivered()
    {
        return $this->belongsTo(SaleDistributor::class, 'delivered_by', 'id');
    }
}
