<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;

class SmsQuota extends Model
{
    use AddingCreatedBy;
    protected $guarded = ['id'];


    public function company(){
        return $this->belongsTo('App\Company', 'fk_company_id', 'id');
    }
    
}
