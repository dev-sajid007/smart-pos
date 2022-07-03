<?php

namespace App\Http\Controllers\Product;

use App\Customer;
use App\CustomerPricing;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class CustomerProductPricingController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $customers = Customer::companies()->pluck('name', 'id');

        $products = Product::companies()->select( 'id', 'product_code as code', 'product_name as name', 'product_price as price')
            ->when($request->filled('customer_id'), function ($qr) use ($request) {
            $qr->withCount(['pricing as customer_price' => function($q) use($request) {
                $q->where('customer_id', $request->customer_id)->select('price');
            }]);
        })->paginate(10);

        return view('admin.product.customer-pricing.create', compact('customers', 'products'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $customer_id = $request->customer_id;

        foreach ($request->customer_prices as $key => $price) {
            if ($price != '') {
                $pricing = CustomerPricing::updateOrCreate([
                    'customer_id' => $customer_id,
                    'product_id'  => $request->product_ids[$key],
                ],['price'       => $price ]);
            }
        }
        return back()->withSuccess('Customer Pricing Updated Successfully');
    }

}
