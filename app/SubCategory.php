<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'fk_category_id');
    }

    public function scopeCompanies($query)
    {
        return $query->where('fk_company_id', companyId());
    }
}
