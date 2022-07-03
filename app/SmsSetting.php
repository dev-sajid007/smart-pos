<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsSetting extends Model
{
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeActive($query)
    {
        return $query->whereStatus(1);
    }
}
