<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class marketing extends Model
{
    protected $guarded = ['id'];
    protected $fillable = ['sale_id','marketers_id', 'marketers_commission'];

    public function sales()
    {
        return $this->belongsTo('App\sale', 'sale_id');
    }

    public function marketers()
    {
        return $this->belongsTo('App\marketer', 'marketers_id');
    }

}
