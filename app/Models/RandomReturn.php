<?php

namespace App\Models;

use App\Models\Inventory\InventoryReturn;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\Wastage;
use App\ProductStock;
use App\Transaction;
use App\Customer;
use App\Company;
use App\User;

class RandomReturn extends Model
{
    public static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()){
            static::creating(function ($model){
                $model->fill([
                    'fk_company_id' => auth()->user()->fk_company_id,
                    'fk_created_by' => auth()->id(),
                ]);
            });

            static::updating(function ($model){
                $model->fill([
                    'fk_updated_by' => auth()->id(),
                ]);
            });


            static::deleting(function ($model) {
                foreach ($model->items as $item) {
                    $productStock = ProductStock::where('fk_product_id', $item->product_id)->where('fk_company_id', $model->fk_company_id)->where('warehouse_id', null)->first();

                    if ($item->condition)
                    {
                        $productStock->update([
                            'available_quantity' => $item->product->product_stock->available_quantity - $item->quantity
                        ]);
                    } else {
                        $productStock->update([
                            'wastage_quantity' => $item->product->product_stock->wastage_quantity - $item->quantity
                        ]);
                    }
                }

                optional($model->transaction)->delete();
                optional($model->items)->each->delete();
                optional($model->returns)->each->delete();
                optional($model->wastages)->each->delete();
            });
        }
    }

    protected $guarded = [];

    protected $dates = [
        'date'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'fk_company_id');
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'fk_created_by');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'fk_customer_id');
    }

    public function consumer()
    {
        return $this->customer();
    }

    public function items()
    {
        return $this->hasMany(RandomReturnItem::class, 'random_return_id');
    }

    public function returns()
    {
        return $this->morphMany(InventoryReturn::class, 'returnable');
    }

    public function wastages()
    {
        return $this->morphMany(Wastage::class, 'wastagable');
    }

    public function generate($id, $quantity, $type)
    {
        return $this->$type()->create([
            'quantity' => $quantity,
            'itemable_type' => RandomReturnItem::class,
            'itemable_id' => $id
        ]);
    }

    public function getInvoiceIdAttribute()
    {
        return '#PR-'.str_pad($this->id, 5, '0', 0);
    }

    public function sumItemsAmount()
    {
        return $this->items()->groupBy('random_return_id')->selectRaw('random_return_id, sum(amount) as total_return');
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
