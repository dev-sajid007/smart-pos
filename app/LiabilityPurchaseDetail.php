<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiabilityPurchaseDetail extends Model
{
    protected $guarded = ['id'];

    public function liability()
    {
        return $this->belongsTo(Liability::class, 'liability_id', 'id');
    }
}
