<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyPackage extends Model
{

    protected $guarded = ['id'];

    public function company(){
        return $this->belongsTo('App\Company', 'fk_company_id', 'id');
    }

    public function billing_cycle(){
        return $this->belongsTo('App\BillingCycle', 'fk_billing_cycle_id', 'id');
    }


}
