<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name','phone','email','website','address', 'account_linked'];

    public function quotation()
    {
        return $this->belongsTo('App\Quotation', 'fk_company_id', 'id');
    }

    public function purchase()
    {
        return $this->belongsTo('App\Purchase', 'fk_company_id', 'id');
    }

    public function sale()
    {
        return $this->belongsTo('App\Sale', 'fk_company_id', 'id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'fk_company_id', 'id');
    }

    public function company_packages()
    {
        return $this->hasMany('App\CompanyPackage', 'fk_company_id', 'id');
    }


    public function sms_quota()
    {
        return $this->hasOne('App\SmsQuota', 'fk_company_id', 'id');
    }

    public function sms_quota_quantity()
    {
        return $this->sms_quota()->first()->quantity;
    }
}
