<?php

namespace App\Http\Controllers;

use App\taxRate;
use Illuminate\Http\Request;
use Auth;

class TaxRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_tax_rate=taxRate::where('fk_company_id', Auth::user()->fk_company_id)->paginate(10);
        return view('admin.settings.tax_rate.index')->with('all_tax_rate',$all_tax_rate);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.settings.tax_rate.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'tax_rate_title'=>'required|max:160|unique:tax_rates',
            'amount'=>'required',
            'tax_rate_type'=>'required'
        ]);

        $tax_rate=new taxRate;

        $tax_rate->tax_rate_title=$request->tax_rate_title;
        $tax_rate->amount=$request->amount;
        $tax_rate->tax_rate_type=$request->tax_rate_type;
        $tax_rate->fk_created_by=$request->user_id;
        $tax_rate->fk_updated_by=$request->user_id;
        $tax_rate->fk_company_id=$request->fk_company_id;

        $tax_rate->save();

        if($tax_rate){
            return \redirect('settings/tax_rates')->with(['success'=>'Tax Rate Created Successfuly!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\taxRate  $taxRate
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $taxrate = taxRate::findOrFail($id);
        return view('admin.settings.tax_rate.show',['taxrate' => $taxrate]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\taxRate  $taxRate
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $tax = taxRate::findOrFail($id);
        return view('admin.settings.tax_rate.edit', ['tax' => $tax]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\taxRate  $taxRate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'tax_rate_title'=>'required|max:160',
            'amount'=>'required',
            'tax_rate_type'=>'required'
        ]);
        //dd($request);
        $stts=taxRate::where('id',$id)
                        ->update(array('tax_rate_title' => $request->tax_rate_title,
                                        'amount'=>$request->amount,
                                        'tax_rate_type'=>$request->tax_rate_type,
                                        'fk_updated_by'=>$request->user_id));
        if($stts){
            return \redirect('settings/tax_rates')->with(['message'=>'Tax Rate Updated Successfuly!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\taxRate  $taxRate
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stts=taxRate::where('id',$id)->delete($id);
        if($stts){
            return \redirect('settings/tax_rates')->with(['message'=>'Category Deleted Successfuly!']);
        }
    }


    public function get_json_list(Request $request){
        if($request->ajax()){
            $tax_rate_list = taxRate::where('fk_company_id', Auth::user()->fk_company_id)->pluck('tax_rate_title', 'id');
            return \response()->json($tax_rate_list);
        }
    }
}
