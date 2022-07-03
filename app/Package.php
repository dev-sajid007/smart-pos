<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{

    protected $guarded = ['id'];

    public function billing_cycle(){
        return $this->belongsTo('App\BillingCycle', 'fk_billing_cycle_id', 'id');
    }

}
