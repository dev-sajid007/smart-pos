<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class marketersledger extends Model
{
    protected $guarded = ['id'];

    public function marketers()
    {
        return  $this->belongsTo(marketer::class, 'marketers_id', 'id');
    }
}
