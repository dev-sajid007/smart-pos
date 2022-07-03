<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsApi extends Model
{
    protected $guarded = ['id'];

    public function scopeCompanies($query)
    {
        $query->where('company_id', auth()->user()->fk_company_id);
    }
}
