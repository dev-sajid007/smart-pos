<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class VoucherAccountChart extends Model
{
    use AddingCreatedBy;
    protected $guarded = ['id'];

    public function voucher()
    {
        return $this->belongsTo('App\Voucher', 'fk_voucher_id', 'id');
    }

    public function account_chart()
    {
        return $this->belongsTo('App\AccountChart','fk_account_chart_id');
    }

    public function transactions()
    {
        return $this->morphMany('App\Transaction', 'transactionable');
    }

    public function amount()
    {
        return $this->hasOne('App\Transaction', 'transactionable_id')
        ->selectRaw('sum(amount) as sum_amount, transactionable_type')
        ->groupBy('transactionable_type');
    }

    public function amountCount()
    {
        return Arr::get($this->amount, 'sum_amount', 0);
    }

    public function chartPayments()
    {
        return $this->hasMany(VoucherChartPayment::class, 'fk_voucher_account_chart_id');
    }

    public function amountPaid()
    {
        return $this->hasOne(VoucherChartPayment::class, 'fk_voucher_account_chart_id')
            ->selectRaw('sum(amount) amount, transactionable_type')
            ->join('transactions', function ($join){
                $join->on('transactionable_id', '=', 'voucher_chart_payments.id')
                ->where('transactionable_type', VoucherChartPayment::class);
            })
            ->groupBy('transactionable_type');
    }

    public function getPaidAttribute()
    {
        return abs(Arr::get($this->amountPaid, 'amount', 0));
    }
}
