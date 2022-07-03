<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use ReflectionClass;

class AccountReview extends Model
{

    protected $guarded = [''];

    public static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()){
            static::deleting(function ($model){
                optional($model->transaction)->delete();
            });
        }
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id')->select('account_name', 'id');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id')->select('method_name', 'id');
    }

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function makeOpeningDue($amount)
    {
        return  $this->transaction()->create([
            'fk_account_id' => defaultAccount()->id,
            'amount' => $amount
        ]);

    }

    public function makeTransaction($amount, $accountId)
    {
        return  $this->transaction()->create([
            'fk_account_id' => $accountId,
            'date' => request('date'),
            'amount' => $amount
        ]);
    }

    public function updateBalance($transactionId, $amount)
    {
        $this->balance()->create([
            'transaction_id' => $transactionId,
            'amount' => $amount
        ]);

    }

    public function consumer()
    {
        return $this->morphTo('consumer', 'transactionable_type', 'transactionable_id', 'id');
    }

    public function isSupplier()
    {
        return get_class($this->transactionable) == Supplier::class;
    }

    public function  getInvoiceIdAttribute()
    {
        if ($this->isSupplier()){
            return "#SDP-".str_pad($this->id, 5, '0', 0);
        }
        return "#CDC-".str_pad($this->id, 5, '0', 0);
    }



    public function created_user()
    {
        return $this->belongsTo(User::class, 'fk_created_by')->select('id', 'name');
    }

    public function approved_user()
    {
        return $this->belongsTo(User::class, 'approved_by')->select('id', 'name');
    }



    public function scopeUserLog($query)
    {
        return $query->with('created_user', 'approved_user');
    }

}
