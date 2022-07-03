<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{

    public static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()){

            static::creating(function ($model){
                $sale_max_id = $model::max('id') + 1;
                $model->fill([
                    'fk_company_id' => auth()->user()->fk_company_id,
                    'fk_created_by' => auth()->id(),
                    'fk_updated_by' => auth()->id(),
                    'fk_status_id' => Status::where('name', 'Completed')->first()->id,
                    'sale_reference' => 'sale-'.$sale_max_id
                ]);
            });

            static::updating(function ($model){
                $model->fill([
                    'fk_updated_by' => auth()->id(),
                ]);
            });

            // static::deleting(function($model){

            //     optional($model->saleMeta)->delete();

            //     foreach ($model->sale_details as $item){
            //         $productStock = ProductStock::where('fk_product_id', $item->fk_product_id)->where('fk_company_id', $model->fk_company_id)->where('warehouse_id', $item->warehouse_id)->first();

            //         if ($productStock) {
            //             $productStock->update([
            //                 'sold_quantity' => $productStock->sold_quantity -= $item->quantity,
            //                 'available_quantity' => $productStock->available_quantity += $item->quantity,
            //             ]);
            //         }
            //     }

            //     $model->sale_details->each->delete();
            //     optional($model->transaction)->delete();
            // });
        }
    }
    protected $guarded = ['id'];
    protected $dates = ['sale_date'];

    public function  getInvoiceIdAttribute()
    {
        return "#S-".str_pad($this->id, 5, '0', 0);
    }

    public function payments()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function sale_customer()
    {
        return $this->hasOne('App\Customer', 'id', 'fk_customer_id');
    }

    public function saleMeta()
    {
        return $this->hasOne(SaleMeta::class);
    }

    public function sale_status()
    {
        return $this->hasOne('App\Status', 'id', 'fk_status_id');
    }

    public function sale_details()
    {
        return $this->hasMany(SalesDetails::class, 'fk_sales_id', 'id');
    }

    public function sale_company()
    {
        return $this->hasOne('App\Company', 'id', 'fk_company_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class,'fk_customer_id','id');
    }
    public function consumer()
    {
        return $this->customer();
    }
    public function transaction()
    {
        return $this->morphOne(Transaction::class,'transactionable');
    }

    public function getPaidAttribute()
    {
        return $this->transaction->amount ?? 0;
    }

    public function getInvoiceAmountAttribute()
    {
        return $this->sub_total - $this->invoice_discount + $this->invoice_tax;
    }

    public function getPayableAmountAttribute()
    {
        $amount = $this->invoiceAmount - $this->advanced + $this->previous_due;
        return $amount < 0 ? 0 : $amount;
    }

    public function getDueAttribute()
    {
        if (!$this->payableAmount){
            return 0;
        }
        return $this->invoiceAmount - $this->paid - $this->advanced + $this->previous_due;
    }


    public function productReturn()
    {
        return $this->hasMany(SaleReturn::class, 'sale_id');
    }

    public function returnedItems()
    {
        return $this->sale_details()->whereHas('itemReturn');
    }
    public function wastedItems()
    {
        return $this->sale_details()->whereHas('itemWasted');
    }

    public function totalReturnItems()
    {
        return $this->sale_details()->whereHas('itemReturn')
            ->orWhereHas('itemWasted');
    }

    public function returns()
    {
        return $this->returnedItems->merge($this->wastedItems);
    }

    public function availableBag()
    {
        return $this->paid - $this->productReturn->sum('change_amount');
    }

    public function currier()
    {
        return $this->belongsTo(Currier::class)->select('name', 'id');
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
    public function marketers()
    {
        return $this->belongsTo(marketer::class, 'marketers_id', 'id');
    }

    public function scopeUserLog($query)
    {
        return $query->with('created_user', 'updated_user');
    }

}
