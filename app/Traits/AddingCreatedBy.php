<?php
/**
 * Created by PhpStorm.
 * User: dev7
 * Date: 11/19/19
 * Time: 3:21 PM
 */

namespace App\Traits;


trait AddingCreatedBy
{
    public static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()){


            static::creating(function ($model){
                $model->fill([
                    'fk_company_id' => auth()->user()->fk_company_id,
                    'fk_created_by' => auth()->id(),
                    'fk_updated_by' => auth()->id(),
                ]);

            });
            static::updating(function ($model){
                $model->fill([
                    'fk_updated_by' => auth()->id(),
                ]);

            });
        }
    }
}