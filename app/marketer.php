<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class marketer extends Model
{
    protected $guarded = ['id'];
    protected $fillable = ['marketers_name', 'marketers_mobile'];

    public function marketersDetails()
    {
       return  $this->hasMany(marketers_details::class, 'marketers_id', 'id');
    }
}
