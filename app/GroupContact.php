<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupContact extends Model
{
    protected $guarded = ['id'];

    public function group(){
        return $this->belongsTo('App\Group', 'fk_group_contact_id', 'id');
    }
}
