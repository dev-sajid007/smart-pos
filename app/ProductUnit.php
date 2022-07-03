<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    use AddingCreatedBy;
    protected $guarded = ['id'];

    public function products()
    {
        return $this->hasMany('App\Product', 'fk_product_unit_id', 'id');
    }

    public function scopeActive($query, $status = 1)
    {
        return $query->whereStatus($status);
    }

    public function scopeCompanies($query)
    {
        return $query->where('fk_company_id', auth()->user()->fk_company_id);
    }


    public function created_user()
    {
        return $this->belongsTo(User::class, 'fk_created_by')->select('id', 'name');
    }

    public function updated_user()
    {
        return $this->belongsTo(User::class, 'fk_updated_by')->select('id', 'name');
    }

    public function scopeUserLog($query)
    {
        return $query->with('created_user', 'updated_user');
    }
}
