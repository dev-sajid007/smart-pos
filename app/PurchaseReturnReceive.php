<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturnReceive extends Model
{
    protected $guarded = ['id'];

    public function receive_details()
    {
        return $this->hasMany(PurchaseReturnReceiveDetail::class);
    }

    public function purchase_return()
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function scopeCompanies($query)
    {
        return $query->where('company_id', auth()->user()->fk_company_id);
    }

    public function scopeUserLog($query)
    {
        return $query->with('created_user');
    }
}
