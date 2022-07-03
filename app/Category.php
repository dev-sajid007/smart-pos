<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = ['id'];
    
    public function subcategories()
    {
        return $this->hasMany(SubCategory::class, 'fk_category_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'fk_category_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'fk_category_id', 'id');
    }



    public function created_user()
    {
        return $this->belongsTo(User::class, 'fk_created_by')->select('id', 'name');
    }

    public function updated_user()
    {
        return $this->belongsTo(User::class, 'fk_updated_by')->select('id', 'name');
    }

    public function scopeCompanies($query)
    {
        return $query->where('fk_company_id', companyId());
    }

    public function scopeUserLog($query)
    {
        return $query->with('created_user', 'updated_user');
    }
}
