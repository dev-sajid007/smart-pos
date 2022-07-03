<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FundTransfer extends Model
{
    protected $guarded = ['id'];


    public function company()
    {
        return $this->belongsTo(Company::class, 'fk_company_id', 'id');
    }
    public function fromAccount()
    {
        return $this->belongsTo(Account::class, 'fk_from_account_id', 'id');
    }
    public function toAccount()
    {
        return $this->belongsTo(Account::class, 'fk_to_account_id', 'id');
    }


    public function transaction()
    {
        return $this->morphOne(Transaction::class,'transactionable');
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
