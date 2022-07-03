<?php

namespace App;

use App\Permission;
use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use AddingCreatedBy;
    protected $guarded = ['id'];


    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }


}
