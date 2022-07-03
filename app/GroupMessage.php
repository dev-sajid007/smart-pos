<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    protected $guarded = ['id'];

    public function sms_customer(){
        $this->hasMany('App\SmsCustomer');
    }

    public function sms_supplier(){
        $this->hasMany('App\SmsSupplier');
    }
}
