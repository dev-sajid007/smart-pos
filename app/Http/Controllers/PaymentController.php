<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::companies()->userLog()->get();
        return view('admin.account.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.account.payments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'method_name' => 'required|unique:payments,method_name'
        ]);

        $payment = Payment::create([
            'method_name'   => $request->method_name,
            'status'        => $request->status,
            'fk_created_by' => $request->user_id,
            'fk_updated_by' => $request->user_id,
            'fk_company_id' => $request->company_id
        ]);

        if ($payment) {
            return redirect()->route('payments-method.index')->withSuccess('Payment Method Created Successfully!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payament $payament
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        return view('admin.account.payments.edit', ['payment' => $payment]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Payament $payament
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $paymentsMethod)
    {
        $attributes = $this->validate($request, [
            'method_name' => 'required|unique:payments,method_name,' . $paymentsMethod->id
        ]);
        $paymentsMethod->update($attributes);

        return redirect()->route('payments-method.index')->withSuccess('Payment Method Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payament $payament
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $stts = Payment::where('id', $id)->delete($id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error_message', 'This Payment Method is use in another table, you can not delete it!');
        }

        return redirect()->route('payments-method.index')->withSuccess('Payment Method Deleted Successfully!');

    }

    public function status($id)
    {
        $stts = Payment::findOrFail($id);
        if ($stts->status == 0) {
            Payment::where('id', $id)->update(array('status' => 1));
        } elseif ($stts->status == 1) {
            Payment::where('id', $id)->update(array('status' => 0));
        }
        return redirect()->route('payments-method.index')->withSuccess('Status Updated Successfully!');
    }
}
