<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;

class QuotationDetails extends Model
{
    use AddingCreatedBy;
    protected $guarded = ['id'];
    public function quotation(){
        return $this->belongsTo('App\Quotation', 'fk_quotation_id', 'id');
    }

    public function product(){
        return $this->belongsTo('App\Product', 'fk_product_id', 'id');
    }
    
}
