<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    public static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()){


            static::creating(function ($model){
                $quotationMaxId = $model::max('id') + 1;
                    $model->fill([
                    'fk_company_id' => auth()->user()->fk_company_id,
                    'fk_created_by' => auth()->id(),
                    'fk_updated_by' => auth()->id(),
                    'quotation_reference' => 'quotation-'.$quotationMaxId,
                    'quotation_date' => date('Y-m-d'),
                    'fk_status_id' => Status::where('name', 'Pending')->first()->id,
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


    public function quotation_details()
    {
        return $this->hasMany('App\QuotationDetails', 'fk_quotation_id', 'id');
    }

    public function quotation_company(){
        return $this->hasOne('App\Company', 'id', 'fk_company_id');
    }

    public function quotation_customer(){
        return $this->hasOne('App\Customer', 'id', 'fk_customer_id');
    }

    public function quotation_status(){
        return $this->hasOne('App\Status', 'id', 'fk_status_id');
    }
}
