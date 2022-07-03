<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $guarded = ['id'];

    public function balancable()
    {
        return $this->morphTo();
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
