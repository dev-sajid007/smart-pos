<?php

namespace App\Models\Inventory;

use App\Company;
use App\Product;
use App\Traits\AddingCreatedBy;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ProductDamage extends Model
{
    use AddingCreatedBy;
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(ProductDamageItem::class, 'product_damage_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'fk_company_id', 'id');
    }

    public function scopeCompanies($query)
    {
        return $query->where('fk_company_id', companyId());
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
