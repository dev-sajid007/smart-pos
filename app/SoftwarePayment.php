<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class SoftwarePayment extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        static::creating(function($model){
            $user = Auth::user();
            $model->fill([
                'fk_created_by' => $user->id,
                'fk_updated_by' => $user->id 
            ]);
        });
        static::updating(function($model){
            $model->fill([
                'fk_updated_by'=>auth()->user()->id,
                'fk_company_id'=>auth()->user()->fk_company_id,
                'fk_company_package_id'=>1,
            ]);
        });
    }

    public function company(){
        return $this->belongsTo('App\Company', 'fk_company_id', 'id');
    }
}
