<?php

namespace App\Http\Controllers;

use App\Discount;
use Illuminate\Http\Request;
use Auth;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts=Discount::where('fk_company_id', Auth::user()->fk_company_id)->paginate(10);
        return view('admin.settings.discounts.index')->with('all_discount',$discounts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.settings.discounts.create');
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
            'title'=>'required|max:160|unique:discounts',
            'amount'=>'required',
            'type'=>'required'
        ]);

        $discounts=new Discount;

        $discounts->title=$request->title;
        $discounts->amount=$request->amount;
        $discounts->type=$request->type;
        $discounts->fk_created_by=$request->user_id;
        $discounts->fk_updated_by=$request->user_id;
        $discounts->fk_company_id=$request->fk_company_id;

        $discounts->save();

        if($discounts){
            return \redirect('settings/discounts')->with(['message'=>'Discount Created Successfuly!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $discount = Discount::findOrFail($id);
        return view('admin.settings.discounts.show', ['discount' => $discount]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        return view('admin.settings.discounts.edit', ['discount' => $discount]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'title'=>'required|max:160',
            'amount'=>'required',
            'type'=>'required'
        ]);
        //dd($request);
        $stts=Discount::where('id',$id)
                        ->update(array('title' => $request->title,
                                        'amount'=>$request->amount,
                                        'type'=>$request->type,
                                        'fk_updated_by'=>$request->user_id));
        if($stts){
            return \redirect('settings/discounts')->with(['message'=>'Discount Updated Successfuly!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stts=Discount::where('id',$id)->delete($id);
        if($stts){
            return \redirect('settings/discounts')->with(['message'=>'Discount Deleted Successfuly!']);
        }
    }


    public function get_json_list(Request $request){
        if($request->ajax()){
            $discount_list = Discount::where('fk_company_id', Auth::user()->fk_company_id)->pluck('title', 'id');
            return response()->json($discount_list);
        }
    }
}
