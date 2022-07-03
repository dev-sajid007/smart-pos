<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use AddingCreatedBy;

    protected $guarded = ['id'];

    protected $dates = [
        'date'
    ];

    public function account()
    {
        return $this->belongsTo('App\Account', 'id', 'fk_account_id');
    }

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function sale()
    {
        return $this->morphTo(Sale::class);
    }

    public function fundTransfer()
    {
        return $this->morphTo(FundTransfer::class);
    }

//    public function balance()
//    {
//        return $this->hasOne(Balance::class, 'transaction_id');
//    }


    public function bySupplier()
    {
        return get_class($this->consumer) == Supplier::class;
    }
    public function byParty()
    {
        return get_class($this->consumer) == Voucher::class;
    }
    public function byCustomer()
    {
        return get_class($this->consumer) == Customer::class;
    }
    public function byPayment()
    {
        return get_class($this->transactionable) == AccountReview::class;
    }

    public function getConsumerAttribute()
    {
        return $this->transactionable->consumer;
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

    public function scopeIncomeExpenses($query)
    {
        return $query->where(function ($qur) {
            $qur->whereHasMorph('transactionable', [AccountReview::class], function ($q) {
                $q->whereHasMorph('transactionable', [Supplier::class], function ($qr) {
                    $qr->where('fk_company_id', auth()->user()->fk_company_id);
                });
            })
            ->orWhereHasMorph('transactionable', [Purchase::class], function ($q) {
                $q->where('fk_company_id', auth()->user()->fk_company_id);
            });
        });
    }
}
