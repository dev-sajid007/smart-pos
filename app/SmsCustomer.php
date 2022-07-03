<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;

class SmsCustomer extends Model
{
    use AddingCreatedBy;
    protected $guarded = ['id'];

    public function group_message(){
        return $this->belongsTo('App\GroupMessage');
    }
}
