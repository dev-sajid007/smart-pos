<?php

namespace App\Http\Controllers;

use App\SmsApi;
use Illuminate\Http\Request;

class SmsApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $smsApis = SmsApi::companies()->get();
        return view('admin.sms-api.settings.index', compact('smsApis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sms-api.settings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'api_title'             => 'required',
            'api_url'               => 'required',
            'get_sms_balance_rul'   => 'required',
            'status'                => 'required'
        ]);

        try {
            $request['company_id'] = auth()->user()->fk_company_id;

            SmsApi::create($request->all());
            return redirect()->back()->withSuccess('Sms Api Created Successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SmsApi $smsApi)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SmsApi $smsApi)
    {
        return view('admin.sms-api.settings.edit', compact('smsApi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SmsApi $smsApi)
    {
        $this->validate($request, [
            'api_title'             => 'required',
            'api_url'               => 'required',
            'get_sms_balance_rul'   => 'required',
            'status'                => 'required'
        ]);

        try {
            $smsApi->update($request->all());
            return redirect()->back()->withSuccess('Sms Api Updated Successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }
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
