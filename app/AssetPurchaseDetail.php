<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetPurchaseDetail extends Model
{
    protected $guarded = ['id'];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'id');
    }
}
