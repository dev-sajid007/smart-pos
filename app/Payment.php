<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use AddingCreatedBy;
    protected $guarded = ['id'];
    protected $table = 'payments';

    public function voucher_chart_payments()
    {
        return $this->hasMany('App\VoucherChartPayment', 'fk_payemnt_id', 'id');
    }

    public function voucher_payments()
    {
        return $this->hasMany('App\VoucherPayment', 'fk_payment_id', 'id');
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
