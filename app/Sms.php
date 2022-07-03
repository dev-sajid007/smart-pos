<?php

namespace App;

use App\Traits\AddingCreatedBy;
use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    use AddingCreatedBy;
    protected $guarded = ['id'];
}
