<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeveloperSetting extends Model
{
    protected $guarded = ['id'];

    public function scopeCompanies($query)
    {
        return $query->where('company_id', auth()->user()->fk_company_id);
    }
}
