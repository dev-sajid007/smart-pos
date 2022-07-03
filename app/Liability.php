<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Liability extends Model
{
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        if (!app()->runningInConsole() ){
            static::creating(function ($model) {
                $model->fill([
                    'company_id' => auth()->user()->fk_company_id,
                    'created_by' => auth()->id(),
                ]);
            });

            static::updating(function ($model){
                $model->fill([
                    'updated_by' => auth()->id(),
                ]);
            });
        }
    }
}
