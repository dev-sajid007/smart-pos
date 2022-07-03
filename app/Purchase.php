<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Purchase extends Model
{
    public static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()){

            static::creating(function ($model){
                $purchase_maximum_id = $model::max('id') + 1;
                $model->fill([
                    'fk_company_id' => auth()->user()->fk_company_id,
                    'fk_created_by' => auth()->id(),
                    'fk_updated_by' => auth()->id(),
                    'purchase_reference' => 'purchase-'.$purchase_maximum_id,
                    'fk_status_id' => Status::where('name', 'Pending')->first()->id
                ]);

            });
            static::updating(function ($model){
                $model->fill([
                    'fk_updated_by' => auth()->id(),
                ]);

            });

            static::deleting(function($model){

                $model->purchase_details->each->delete();
                optional($model->transaction)->delete();
            });
        }
    }

    public function scopeCompany()
    {
        return $this->where('fk_company_id', auth()->user()->fk_company_id);
    }

    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('id', 'like', "%{$query}%")
                ->orWhere('purchase_date', 'like', "%{$query}%")
                ->orWhereHas('supplier', function ($q)use($query){
                    $q->where('name', 'like', "%{$query}%");
                });
    }

    public function scopeTotalAmount($query)
    {
        return $query->withCount(['purchase_details as total_payable' => function($q) {
            $q->select(DB::Raw('sum(quantity*unit_price) as total'));
        }]);
    }

    protected $guarded = ['id'];

    protected $dates = [
        'purchase_date'
    ];

    //Invoice Amount
    public function getInvoiceAmountAttribute()
    {
        return $this->sub_total - $this->invoice_discount + $this->invoice_tax;
    }

    public function purchase_company()
    {
        return $this->hasOne('App\Company', 'id', 'fk_company_id');
    }

    public function purchase_supplier()
    {
        return $this->hasOne('App\Supplier', 'id', 'fk_supplier_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'fk_supplier_id', 'id');
    }

    public function consumer()
    {
        return $this->supplier();
    }

    public function purchase_details()
    {
        return $this->hasMany(PurchaseDetails::class, 'fk_purchase_id', 'id');
    }


    public function productQuantity()
    {
        return $this->hasMany('App\PurchaseDetails', 'fk_purchase_id', 'id')
        ->selectRaw('fk_product_id, sum(quantity) as quantity')
        ->groupBy('fk_product_id');
    }

    public function purchase_status()
    {
        return $this->hasOne('App\Status', 'id', 'fk_status_id');
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class,'transactionable');
    }

    public function approved()
    {
        $this->fk_status_id = Status::where('name', 'Completed')->first()->id;
        $this->save();
    }
    
    public function isApproved()
    {
        return $this->fk_status_id == Status::where('name', 'Completed')->first()->id;
    }




    public function advancedAmount()
    {
        $amount = $this->previous_due;

        return $amount < 0 ? abs($amount) : 0;
    }

    public function previousDue()
    {
        $amount = $this->previous_due;

        return  $amount > 0 ? $amount : 0;
    }

    public function getPaidAttribute()
    {
        return abs($this->transaction->amount ?? 0);
    }

    public function getDueAttribute()
    {
        return $this->totalPayableAmount() - $this->paid;
    }

    public function totalPayableAmount()
    {
        return $this->invoiceAmount - $this->advancedAmount() + $this->previousDue();
    }

    public function  getInvoiceIdAttribute()
    {
        return "#P-".str_pad($this->id, 5, '0', 0);
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
