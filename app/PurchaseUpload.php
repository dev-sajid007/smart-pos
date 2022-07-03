<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseUpload extends Model
{
    protected $guarded = ['id'];


    public static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()){
            static::creating(function ($model) {
                $model->fill([
                    'company_id' => auth()->user()->fk_company_id,
                ]);
            });
        }
    }
}
