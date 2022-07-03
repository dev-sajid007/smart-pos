<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{

    public static function boot()
    {

        parent::boot();
        if (!app()->runningInConsole()){


            static::creating(function ($model){
                $party_max_id = $model::max('id')+1;
                $model->fill([
                    'fk_company_id' => auth()->user()->fk_company_id,
                    'fk_created_by' => auth()->id(),
                    'fk_updated_by' => auth()->id(),
                    'party_code' => 'party-'.$party_max_id,
                ]);

            });
            static::updating(function ($model){
                $model->fill([
                    'fk_updated_by' => auth()->id(),
                ]);

            });
        }
    }
    protected $guarded = ['id'];

    public function voucher()
    {
        return $this->belongsTo('App\Voucher', 'fk_party_id', 'id');
    }

    public function scopeCompanies($query)
    {
        return $query->where('fk_company_id', auth()->user()->fk_company_id);
    }


    public function created_user()
    {
        return $this->belongsTo(User::class, 'fk_created_by')->select('id', 'name');
    }

    public function updated_user()
    {
        return $this->belongsTo(User::class, 'fk_updated_by')->select('id', 'name');
    }

    public function scopeUserLog($query)
    {
        return $query->with('created_user', 'updated_user');
    }
}
