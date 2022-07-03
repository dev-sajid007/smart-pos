<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;

class VoucherPayment extends Model
{
    use AddingCreatedBy;
    protected $guarded = ['id'];


    public function voucher_chart_payments()
    {
        return $this->hasMany('App\VoucherChartpayment', 'fk_voucher_payemnt_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo('App\Account', 'fk_account_id');
    }

    public function voucher()
    {
        return $this->belongsTo('App\Voucher', 'id', 'fk_voucher_payemnt_id');
    }

    public function payment()
    {
        return $this->belongsTo('App\Payment', 'id', 'fk_payment_id');
    }
}
