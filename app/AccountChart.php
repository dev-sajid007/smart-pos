<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;

class AccountChart extends Model
{
    public static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()){

            static::creating(function ($model){
                $model->fill([
                    'fk_company_id' => auth()->user()->fk_company_id,
                    'fk_created_by' => auth()->id(),
                    'fk_updated_by' => auth()->id(),
                ]);

            });
            static::updating(function ($model){
                $model->fill([
                    'fk_updated_by' => auth()->id(),
                ]);

            });
        }
    }

    protected $guarded = ['id'];

    public function gl_account()
    {
        return $this->belongsTo(GlAccount::class);
    }

    public function voucher_account_charts()
    {
        return $this->hasMany('App\VoucherAccountChart', 'fk_account_chart_id', 'id');
    }

    public function voucher_credit_account_charts()
    {
        return $this->hasMany('App\VoucherAccountChart', 'fk_account_chart_id', 'id')
            ->whereHas('voucher', function($q){
                $q->where('voucher_type', 'credit');
            });
    }

    public function voucher_debit_account_charts()
    {
        return $this->hasMany('App\VoucherAccountChart', 'fk_account_chart_id', 'id')
            ->whereHas('voucher', function($q){
                $q->where('voucher_type', 'debit');
            });
    }
    public function voucher_chart_payments()
    {
        return $this->hasMany('App\VoucherChartPayment', 'fk_account_chart_id', 'id');
    }

    public function sumCreditAmount()
    {
        $amount = 0;
        foreach($this->voucher_credit_account_charts as $voucherChart)
        {
            $amount+=$voucherChart->amountCount();
        }
        return $amount;
    }

    public function sumDebitAmount()
    {
        $amount = 0;
        foreach($this->voucher_debit_account_charts as $voucherDebitChart){
            $amount += $voucherDebitChart->amountCount();
        }
        return $amount;
    }


    public function transactions()
    {
        return $this->hasOne(VoucherAccountChart::class, 'fk_account_chart_id')
            ->selectRaw(
                'voucher_account_charts.fk_account_chart_id,
                sum(transactions.amount) as amount, voucher_type'
            )
            ->join('voucher_chart_payments', 'voucher_account_charts.id', '=', 'voucher_chart_payments.fk_voucher_account_chart_id')
            ->join('transactions', function (JoinClause $joinClause){
                $joinClause->on('voucher_chart_payments.id', '=', 'transactions.transactionable_id')
                    ->where('transactions.transactionable_type', VoucherChartPayment::class);
            })
            ->join('vouchers', 'voucher_chart_payments.fk_voucher_id', '=', 'vouchers.id')
            ->groupBy('voucher_account_charts.fk_account_chart_id','voucher_type');


    }

    public function scopeIncome($q){
        return $q->where('head_type', 0);
    }

    public function scopeExpense($q){
        return $q->where('head_type', 1);
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
