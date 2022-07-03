<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    
    public function quotation(){
        return $this->belongsTo('App\Quotation', 'fk_status_id', 'id');
    }

    public function purchase(){
        return $this->belongsTo('App\Purchase', 'fk_status_id', 'id');
    }

    public function sale(){
        return $this->belongsTo('App\Sale', 'fk_status_id', 'id');
    }
}
