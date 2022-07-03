<?php

namespace App\Http\Controllers;

use App\Customer;
use App\smsGroup;
use App\SmsSetting;
use App\Supplier;
use Illuminate\Http\Request;

class SmsSettingController extends Controller
{
    public function index()
    {
        $apis = SmsSetting::with('company:name,id')->get();
        return view('admin.sms-api.settings.index', compact('apis'));
    }
    public function sendSms()
    {
        $data['customers'] = Customer::customers()->paginate(100);
        $data['suppliers'] = Supplier::suppliers()->paginate(100);
        $data['smsGroups'] = smsGroup::paginate(100);
        return view('admin.sms-api.send-sms', $data);
    }
    public function store(Request $request){
        // dd($request);
        $request->validate([
            'name'          => 'required',
            'username'      => 'required',
            'password'      => 'required',
            'sender_number' => 'required',
            'url'           => 'required',
        ]);
        $data = array_merge($request->except('_token'), ['company_id' => auth()->user()->fk_company_id]);
        try {
            SmsSetting::create($data);
            return redirect()->back()->with(['message', 'Api create success']);
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return redirect()->back()->with(['error' => 'Something error, please check']);
        }
    }
    public function show($id)
    {
        try {
            SmsSetting::where('status', 1)->update([
                'status' => 0
            ]);
            SmsSetting::find($id)->update([
                'status' => 1
            ]);
            return redirect()->back()->with('message', 'Api activated successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            if (!$request->filled('password')) {
                $data = $request->except('password');
            }
            SmsSetting::find($id)->update($data);
            return redirect()->back()->with('message', 'Api updated successfully');
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            SmsSetting::destroy($id);
        } catch (\Exception $e){
            return redirect()->back()->with('error_message', 'This api use in another table you can not to delete it');
        }
        return redirect()->back()->with(['message' => 'Module Deleted Successfully!']);
    }
    public function getActiveSmsApi(Request $request)
    {
        $currentActiveApi = SmsSetting::active()->first();
        return response()->json($currentActiveApi);
    }
}
