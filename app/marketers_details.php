<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class marketers_details extends Model
{
    protected $guarded = ['id'];
    protected $fillable = ['marketers_id', 'start_amount', 'end_amount', 'marketers_commission'];
}
