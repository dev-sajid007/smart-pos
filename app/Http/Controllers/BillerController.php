<?php

namespace App\Http\Controllers;

use App\Biller;
use Illuminate\Http\Request;
use Auth;

class BillerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $billers = Biller::where('fk_company_id', Auth::user()->fk_company_id)->paginate(10);
       return view('admin.people.billers.index', ['billers'=>$billers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.people.billers.create');
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
            'name' => 'required',
            'biller_code'=>'required|max:160|unique:billers',
            'phone' => 'required|unique:billers|regex: /(01)[0-9]{9}/|size:11',
            'email' => 'required|max:160|unique:billers',
            'address' => 'required'
        ]);

        $biller_save = Biller::create($request->except('_token'));

        if($biller_save){
            return \redirect('people/billers')->with(['message'=>'Biller Created Successfully!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Biller  $biller
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $biller = $this->get_biller($id);
        return view('admin.people.billers.show', ['biller'=>$biller]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Biller  $biller
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $biller = $this->get_biller($id);
        return view('admin.people.billers.edit', ['biller' => $biller]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Biller  $biller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:160|unique:billers,name,'.$request->id,
            'biller_code'=>'required|max:160|unique:billers,biller_code,'.$request->id,
            'phone' => 'required|regex: /(01)[0-9]{9}/|size:11|unique:billers,phone,'.$request->id,
            'email' => 'required|max:160|unique:billers,email,'.$request->id,
            'address' => 'required'
        ]);

        $biller = $this->get_biller($id);
        $biller_update = $biller->update($request->except(['_token']));

        if($biller_update){
            return \redirect('people/billers')->with(['message'=>'Biller Updated Successfully!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Biller  $biller
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $biller = $this->get_biller($id);
        $biller->delete();
        return \redirect('people/billers')->with(['message'=>'Biller Deleted Successfully!']);
    }

    public function get_biller($id){
        return Biller::findOrFail($id);
    }
}
