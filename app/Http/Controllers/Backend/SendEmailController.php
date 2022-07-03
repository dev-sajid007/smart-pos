<?php

namespace App\Http\Controllers\Backend;

use App\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\MailSender;
use App\Purchase;
use Illuminate\Support\Facades\Mail;

class SendEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        if ($request->type = "sale") {
            $data  = Sale::with(['sale_customer'])->withCount(['sale_details as total_sale_amount' => function ($q) {
                $q->select(DB::Raw('SUM(quantity * unit_price)'));
            }])->with(['sale_details' => function ($q) {
                $q->with('product', 'package');
            }])->find($id);
            try {
                if ($data->sale_customer->email) {
                    Mail::to($data->sale_customer->email)->send(new MailSender($data, 'mail.sale_invoice', 'Sale Report'));
                    return back()->withSuccess('Email send successfully!');
                } else {
                    return back()->withSuccess('No Email Found!');
                }
            } catch (\Exception $ex) {
                return back()->withSuccess($ex->getMessage());
            }
        }
    }

    public function purchase($id)
    {
        $purchase =  Purchase::with('purchase_company')->find($id);

        try {
            if ($purchase->purchase_supplier->email) {
                Mail::to($purchase->purchase_supplier->email)->send(new MailSender($purchase, 'mail.customer-invoice', 'Purchase Report'));
                return back()->withSuccess('Email send successfully!');
            } else {
                return back()->withSuccess('No Email Found!');
            }
        } catch (\Exception $ex) {
            return back()->withSuccess($ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
