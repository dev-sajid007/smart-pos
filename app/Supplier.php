<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Supplier extends Model
{
    public static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()){


            static::creating(function ($model){
                $supplier_max_id = $model::max('id')+1;

                $model->fill([
                    'fk_company_id' => auth()->user()->fk_company_id,
                    'fk_created_by' => auth()->id(),
                    'fk_updated_by' => auth()->id(),
                    'supplier_code' => 'supplier-'.$supplier_max_id
                ]);

            });
            static::updating(function ($model){
                $model->fill([
                    'fk_updated_by' => auth()->id(),
                ]);

            });

            static::deleting(function($model){
                optional($model->transaction)->delete();
            });
        }
    }

    protected $guarded = ['id'];

    public function Purchase()
    {
        return $this->belongsTo('App\Purchase', 'fk_supplier_id', 'id');
    }

    public function todayPurchases()
    {
        return $this->purchases()->where('purchase_date', today());
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'fk_supplier_id', 'id');
    }



    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function makeOpeningDue($amount)
    {
        $transaction = $this->transaction()->create([
            'fk_account_id' => defaultAccount()->id,
            'amount' => $amount
        ]);

//        $this->updateBalance($transaction->id, $amount);
    }
//
//    public function updateBalance($transactionId, $amount)
//    {
//        return $this->balances()->create([
//            'transaction_id' => $transactionId,
//            'amount' => $amount
//        ]);
//    }

    public function accountReviews()
    {
        return $this->morphMany(AccountReview::class, 'transactionable');
    }


    public function scopeSuppliers($query)
    {
        return $query->where('fk_company_id', auth()->user()->fk_company_id);
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
