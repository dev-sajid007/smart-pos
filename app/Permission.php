<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = ['id'];

    
    public function role_permission(){
        return $this->belongsTo('App\Permission', 'fk_permission_id', 'id');
    }
    
    public function module(){
        return $this->belongsTo('App\Module', 'fk_module_id', 'id');
    }

    public function roles(){
        return $this->belongsToMany('App\Role', 'role_permissions');
    }

}
