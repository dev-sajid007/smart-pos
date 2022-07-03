<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;

class VoucherChartPayment extends Model
{
    use AddingCreatedBy;
    protected $guarded = ['id'];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'fk_voucher_id');
    }

    public function account_chart()
    {
        return $this->belongsTo('App\AccountChart', 'id', 'fk_account_chart_id');
    }

    public function account()
    {
        return $this->belongsTo('App\Account', 'id', 'fk_account_id');
    }

    public function payment()
    {
        return $this->belongsTo('App\Payment', 'id', 'fk_payment_id');
    }

    public function voucher_payment()
    {
        return $this->belongsTo('App\VoucherPayment', 'fk_voucher_payment_id', 'id');
    }

    public function payments()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function consumer()
    {
        return $this->voucher()
            ->selectRaw('parties.name as name, parties.id, vouchers.fk_party_id, vouchers.id')
            ->join('parties', 'parties.id', '=', 'vouchers.fk_party_id')
            ->groupBy('fk_party_id', 'vouchers.id');
    }

    public function voucher_account_chart(){
        return $this->belongsTo(VoucherAccountChart::class, 'fk_voucher_account_chart_id', 'id');
    }

}
