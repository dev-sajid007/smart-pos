<?php

namespace App\Http\Controllers;

use DateTime;
use App\Company;
use Carbon\Carbon;
use App\CompanyPackage;
use App\SoftwarePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SoftwarePaymentController extends Controller
{
    public function index()
    {
        $software_payments = SoftwarePayment::paginate(30);
        return view('admin.global_config.software_payments.index', compact('software_payments'));
    }

    public function create()
    {
        return view('admin.global_config.software_payments.create');
    }

    public function store(Request $request)
    {
        $input = $request->except('_token', '_method');
        $input['fk_created_by'] = auth()->user()->fk_company_id;
        $input['fk_company_id'] = auth()->user()->fk_company_id;
        $input['fk_company_package_id'] = 1;
        SoftwarePayment::create($input);

        return redirect()->route('software_payments.index')->with('success', 'added Successfully');
    }
    public function show(SoftwarePayment $softwarePayment)
    {
        dd('dddd');
    }
    public function edit($id)
    {
        return view('admin.global_config.software_payments.edit', [
            'softwarePayment' => SoftwarePayment::where('id', $id)->orderBy('id', 'desc')->first()
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'software_payment_date' => 'required',
            'amount' => 'required',
            'alert' => 'required',
            'status' => 'required'
        ]);
        try {
            
            $input = $request->except('_token', '_method');
            if ($input['status'] == '1') {
                $input['paid_date'] = Carbon::now();
            }
            SoftwarePayment::where('id', $id)->update($input);
            return redirect()->route('software_payments.index')->with('success', 'Updated Successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->with('warning',$ex->getMessage());
        }
    }

    public function destroy($id)
    {
        $SoftwarePayment = SoftwarePayment::where('id', $id)->first();
        if ($SoftwarePayment->status != 1) {
            $SoftwarePayment->delete();
            return \redirect('software_payments')->with(['message' => 'Software Payment Deleted Successfully!']);
        } else {
            return \redirect('software_payments')->with(['error_message' => 'This is a paid invoice, can not delete!']);
        }
    }

    public function invoice($id)
    {
        $from_company = Company::findOrFail(auth()->user()->id);
        $software_payment = SoftwarePayment::findOrFail($id);
        return view('admin.global_config.software_payments.invoice', compact([
            'software_payment', 'from_company'
            ]));
    }
    public function paid(Request $request)
    {
		try{
            if($request->get('pay_status') == 'Successful') {
                SoftwarePayment::where('id', $request->order_id)->update([
                            'status'    => 1,
                            'paid_date' => date('y-m-d'),
                        ]);
                return redirect()->route('home')->with('success', 'Payment Successful');
            }
            else{
                return Redirect::to($request->opt_a)->withError('Payment Not Success');
            }

            // if ($request->is_paid != '' || $request->is_paid != null) {
            //     SoftwarePayment::where('id', $request->order_id)->update([
            //         'status'    => 1,
            //         'paid_date' => date('y-m-d'),
            //     ]);
            //     return redirect()->route('software_payments.index')->with('success', 'Payment Successful');
            //     // return Redirect::to($request->opt_a)->withSuccess('Payment Successful');
            // } else {
            //     return redirect()->route('software_payments.index')->with('warning', 'Payment Not Success');
            //     // return Redirect::to($request->opt_a)->withError('Payment Not Success');
            // }
        }
        catch(\Exception $ex){
            return back()->withError('Payment Not Success');
        }
    }
}
