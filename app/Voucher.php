<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Query\JoinClause;

class Voucher extends Model
{
    protected $guarded = ['id'];

    protected $dates = [
        'voucher_date'
    ];

    public static function boot(){
        parent::boot();


        static::creating(function($model){
            $user = auth()->user();
            $model->fill([
                'fk_created_by' => $user->id,
                'fk_updated_by' => $user->id,
                'fk_approved_by' => $user->id,
                'approved_at' => now()->toDate(),
                'fk_company_id' => $user->fk_company_id
            ]);
        });

        static::deleting(function($voucher){
            foreach ($voucher->voucher_chart_payments as $chartPayment){
                $chartPayment->payments->each->delete();
            }
            $voucher->voucher_chart_payments()->delete();
            $voucher->voucher_payment()->delete();
            $voucher->voucher_account_charts()->delete();
        });
    }

    public function party()
    {
        return $this->belongsTo('App\Party','fk_party_id');
    }

    public function voucher_details()
    {
        return $this->hasMany(VoucherAccountChart::class, 'fk_voucher_id', 'id');
    }

    public function scopeTotalAmount($query)
    {
        return $query->withCount(['voucher_details AS total_amount' => function($q) {
            return $q->select(\DB::Raw('SUM(payable_amount)'));
        }]);
    }

    public function voucher_account_charts()
    {
        return $this->hasMany('App\VoucherAccountChart', 'fk_voucher_id', 'id');
    }

    public function voucher_chart_payments()
    {
        return $this->hasMany('App\VoucherChartPayment', 'fk_voucher_id', 'id');
    }

    public function voucher_payment()
    {
        return $this->hasOne(VoucherPayment::class, 'fk_voucher_id', 'id');
    }


    public function transactions()
    {
        return $this->hasOne('App\Transaction', 'transactionable_id', 'id');
    }


    public function isDebit()
    {
        return $this->voucher_type == 'debit';
    }


    public function voucherLiabilites()
    {
        return $this->hasMany(VoucherAccountChart::class, 'fk_voucher_id')
            ->selectRaw(
                'sum(payable_amount) as amount, sum(transactions.amount) as paid, voucher_account_charts.fk_voucher_id'
            )
            ->join('voucher_chart_payments', 'voucher_account_charts.id', '=', 'voucher_chart_payments.fk_voucher_account_chart_id')
            ->join('transactions', function (JoinClause $joinClause){
                $joinClause->on('voucher_chart_payments.id', '=', 'transactionable_id')
                    ->where('transactionable_type', VoucherChartPayment::class);
            })
            ->groupBy('voucher_account_charts.fk_voucher_id');
    }

    public function getPaidAttribute()
    {
        return $this->voucher_account_charts->sum('paid');
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
