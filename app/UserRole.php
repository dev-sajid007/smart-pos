<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;
use Auth;

class UserRole extends Model
{

    use AddingCreatedBy;
    protected $guarded = ['id'];
 


    public function role()
    {
        return $this->belongsTo('App\Role');
    }
}
