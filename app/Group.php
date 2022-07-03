<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $guarded = ['id'];

    public function group_contacts(){
        return $this->hasMany('App\GroupContact', 'fk_group_id', 'id');
    }

    public function total_members(){
        return $this->group_contacts()->count();
    }
}
