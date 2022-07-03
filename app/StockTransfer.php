<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        if (!app()->runningInConsole()) {

            static::creating(function ($model) {
                $model->fill([
                    'from_company_id' => auth()->user()->fk_company_id,
                    'created_by' => auth()->id(),
                ]);

            });

            static::updating(function ($model) {
                $model->fill([
                    'received_by' => auth()->id(),
                ]);
            });

            static::deleting(function($model) {
                $model->stock_transfer_details->each->delete();
            });
        }
    }

    public function stock_transfer_details()
    {
        return $this->hasMany(StockTransferDetail::class, 'stock_transfer_id', 'id');
    }

    public function from_warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id', 'id');
    }

    public function to_warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id', 'id');
    }

    public function from_company()
    {
        return $this->belongsTo(Company::class, 'from_company_id', 'id');
    }

    public function to_company()
    {
        return $this->belongsTo(Company::class, 'to_company_id', 'id');
    }

    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }


}
