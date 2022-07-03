<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public static function boot() {

        parent::boot();

        if(!app()->runningInConsole()){

            static::creating(function($model){
                $model->fill([
                    'email_verified_at' => now(),
                    'password' => \bcrypt('12345678'),
                    'remember_token' => Str::random(10)
                ]);
            });

        }

    }

    public function authorizeRoles($roles){
        if(is_array($roles)){
            foreach($roles as $role){
                if($this->hasRole($roles)){
                    return true;
                }
            }
        }else{
            if($this->hasRole($role)){
                return true;
            }
        }
    }

    public function hasRole($role){
        $role_allowed = UserRole::where([
            'user_id' => Auth::user()->id,
            'role_id' => $role
        ])->first()->role_id;

        if($role_allowed == $role){
            return true;
        }else{
            return false;
        }
    }

    public function scopeCompanies($query)
    {
        return $query->where('fk_company_id', companyId());
    }

    public function scopeNotAdmin($query)
    {
        return $query->where('email', '!=', 'admin@gmail.com');
    }

    public function company()
    {
        return $this->belongsTo('App\Company', 'fk_company_id', 'id');
    }

    public function user_role()
    {
        return $this->hasOne('App\UserRole', 'user_id', 'id');
    }
}
