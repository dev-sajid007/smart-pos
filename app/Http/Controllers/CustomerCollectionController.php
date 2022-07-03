<?php

namespace App\Http\Controllers;

use App\AccountReview;
use App\Customer;
use App\Services\PurchaseService;
use App\Services\SalesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = AccountReview::where('transactionable_type', Customer::class)->paginate(30);
        return view('admin.sales.collection.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sales.collection.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'type' => 'required',
            'amount' => 'required',
        ]);
        DB::transaction(function ()use($request) {
            $customer = Customer::find($request->customer_id);
            $review = $customer->accountReviews()->create([
                'type' => $request->type,
                'amount' => $request->amount
            ]);

            $amount = $request->type == 'due' ? -$request->amount : $request->amount;

            /** @var AccountReview $review */
            $transaction = $review->makeOpeningDue($amount);

        });
        return back()->withSuccess('Customer '.ucfirst($request->type). ' Receive Successfully done');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AccountReview $customerCollection)
    {
        $company = auth()->user()->company;
        return view('admin.sales.collection.show', compact('customerCollection', 'company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AccountReview $customerCollection
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(AccountReview $customerCollection)
    {
        $is_supplier = $customerCollection->transactionable_type == 'App\Supplier';
        

        $customer_id = $customerCollection->transactionable_id;
        $customerCollection->delete();

        if ($is_supplier) {
            // update suplier balance
            (new PurchaseService())->updateSupplierBalance($customer_id);
        } else {
            // update customer balance
            (new SalesService())->updateCustomerBalance($customer_id);
        }
        

        return back()->withSuccess('Transaction Deleted Successfully');
    }
}
