<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlAccount extends Model
{
    protected $guarded = ['id'];


    public function scopeCompanies($query)
    {
        return $query->where('fk_company_id', auth()->user()->fk_company_id);
    }
}
