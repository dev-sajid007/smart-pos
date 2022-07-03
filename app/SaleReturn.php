<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{

    protected $guarded = [];

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
                    'fk_updated_by' => auth()->id(),
                ]);
            });
        }
    }

    protected $dates = ['date'];

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function sale_return_details()
    {
        return $this->hasMany(SaleReturnDetail::class, 'sale_return_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function scopeCompanies($query)
    {
        return $query->where('company_id', auth()->user()->fk_company_id);
    }


    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by')->select('id', 'name');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
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


