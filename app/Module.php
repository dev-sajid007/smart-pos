<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'fk_company_id', 'id');
    }
    public function permissions(){
        return $this->hasMany('App\Permission', 'fk_module_id', 'id');
    }
}
