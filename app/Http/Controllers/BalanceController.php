<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Supplier;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function currentBalance(Request $request)
    {
        if ($request->type == 'supplier'){
            return Supplier::find($request->id)->currentBalance;
        }
        if ($request->type == 'customer'){
            return Customer::find($request->id)->currentBalance;
        }
    }
}
