<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Customer extends Model
{
    public static function boot()
    {
        if (!app()->runningInConsole()){
            parent::boot();

            static::creating(function ($model){
                $customer_max_id = $model::max('id') + 1;
                $model->fill([
                    'fk_company_id' => auth()->user()->fk_company_id,
                    'fk_created_by' => auth()->id(),
                    'fk_updated_by' => auth()->id(),
                    'customer_code' => 'c-'.$customer_max_id,
                ]);

            });
            static::updating(function ($model){
                $model->fill([
                    'fk_updated_by' => auth()->id(),
                ]);

            });

            static::deleting(function($model) {
                optional($model->transaction)->delete();
            });
        }
    }


    protected $guarded = ['id'];

    public function sale()
    {
        return $this->hasMany(Sale::class, 'fk_customer_id');
    }

    public function todaySales()
    {
        return $this->sale()->where('sale_date', today());
    }

    public function customer_category()
    {
        return $this->belongsTo(CustomerCategory::class);
    }


    public function balances()
    {
        return $this->morphMany(Balance::class, 'balancable');
    }

    public function balance()
    {
        return $this->morphOne(Balance::class, 'balancable')
            ->selectRaw('sum(amount) as amount, balancable_id')
            ->groupBy('balancable_id');
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

    public function updateBalance($transactionId, $amount)
    {
        return $this->balances()->create([
            'transaction_id'    => $transactionId,
            'amount'            => $amount
        ]);

    }

    public function accountReviews()
    {
        return $this->morphMany(AccountReview::class, 'transactionable');
    }

    public function advanced()
    {
        return $this->currentBalance >= 0 ? $this->currentBalance : 0;
    }
    public function prevDue()
    {
        return $this->currentBalance <= 0 ? $this->currentBalance : 0;
    }

    public function scopeCustomers($q)
    {
        return $q->where('fk_company_id', auth()->user()->fk_company_id);
    }

    public function scopeCompanies($query)
    {
        return $query->where('fk_company_id', auth()->user()->fk_company_id);
    }

    public function pricing()
    {
        return $this->belongsToMany(Product::class);
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
    public function  getCustomerIdAttribute()
    {
        return "#c-".str_pad($this->id, 5, '0', 0);
    }
}
