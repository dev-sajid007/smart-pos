<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $guarded = ['id'];

    public function role(){
        return $this->hasOne('App\Role', 'id', 'fk_role_id');
    }

//    public function permission(){
//        return $this->hasOne('App\Permission', 'id', 'fk_permission_id');
//    }
}
