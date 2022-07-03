<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use AddingCreatedBy;
    
    protected $guarded = ['id'];

    public function voucher_chart_payments()
    {
        return $this->hasMany('App\VoucherChartPayment', 'fk_account_id', 'id');
    }

    public function voucher_payments()
    {
        return $this->hasMany('App\VoucherPayment', 'fk_account_id', 'id');
    }
    
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function opening_balance()
    {
        return $this->hasOne(Transaction::class, 'fk_account_id', 'id')->where('transactionable_type', 'App\Account')->where('fk_company_id', companyId());
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction', 'fk_account_id', 'id');
    }

    public function income_transactions()
    {
        return $this->hasMany('App\Transaction', 'fk_account_id', 'id')->where('amount', '>', 0)->where('transactionable_type', 'App\VoucherChartPayment');
    }

    public function expense_transactions()
    {
        return $this->hasMany('App\Transaction', 'fk_account_id', 'id')->where('amount', '<', 0)->where('transactionable_type', 'App\VoucherChartPayment');
    }

    public static function getAccountWithBalance()
    {
        $transactions = Transaction::where('transactionable_type', 'App\PurchaseReturn')->get();

        foreach ($transactions as $key => $transaction) {
            $transaction->update([
                'amount' => (-1) * abs($transaction->amount)
            ]);
        }
        
        return  self::companies()->withCount(['transactions AS total_amount' => function ($query) {
            return $query->select(\DB::raw("SUM(amount) as amount"));
        }])->get();
    }

    public function scopeCompanies($query)
    {
        return $query->where('fk_company_id', auth()->user()->fk_company_id);
    }
}
