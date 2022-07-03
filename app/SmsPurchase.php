<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;

class SmsPurchase extends Model
{
    use AddingCreatedBy;
}
