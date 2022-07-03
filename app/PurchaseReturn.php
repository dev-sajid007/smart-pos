<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    protected $guarded = ['id'];


    public static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()){

            static::creating(function ($model) {
                $model->fill([
                    'company_id' => auth()->user()->fk_company_id,
                    'created_by' => auth()->id(),
                ]);
            });

            static::updating(function ($model) {
                $model->fill([
                    'updated_by' => auth()->id(),
                ]);
            });

            static::deleting(function ($model){
                optional($model->transaction)->delete();
                // foreach ($model->wastages as $wastage) {

                //     $wastage->increaseWastageStock(false);

                //     if ($wastage->replaces) {
                //         foreach ($wastage->replaces as $replace) {
                //             $replace->revertStock();
                //         }
                //     }
                //     optional($wastage->replaces)->each->delete();
                // }
            });
        }
    }

    public function purchaseReturnDetails()
    {
        return $this->hasMany(PurchaseReturnDetail::class, 'purchase_return_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function purchase_return_receives()
    {
        return $this->hasMany(PurchaseReturnReceive::class, 'purchase_return_id', 'id');
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function scopeCompanies($query)
    {
        return $query->where('company_id', auth()->user()->fk_company_id);
    }



    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updated_user()
    {
        return $this->belongsTo(User::class, 'updated_by')->select('id', 'name');
    }

    public function scopeUserLog($query)
    {
        return $query->with('created_user', 'updated_user');
    }
}
