<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Biller extends Model
{
    public static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()){

            static::creating(function ($model){
                $biller_max_id = $model::max('id')+1;
                $model->fill([
                    'fk_company_id' => auth()->user()->fk_company_id,
                    'fk_created_by' => auth()->id(),
                    'fk_updated_by' => auth()->id(),
                    'biller_code' => 'biller-'.$biller_max_id
                ]);

            });
            static::updating(function ($model){
                $model->fill([
                    'fk_updated_by' => auth()->id(),
                ]);

            });
        }
    }

    protected $guarded = ['id'];
}
