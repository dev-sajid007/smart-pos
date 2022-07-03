<?php

namespace App\Http\Controllers;

use App\marketer;
use App\marketers_details;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;

class MarketerController extends Controller
{

    public function index()
    {
        return view('admin.people.marketers.index',[
            'marketers' => marketer::paginate(30),
        ]);
    }

    public function create()
    {
        return view('admin.people.marketers.create');
    }

    public function store(Request $request)
    {
        try{
            $data = $request->only('marketers_name','marketers_mobile');
            $marketer = marketer::create($data);
            if($request->start_amount != [] || $request->start_amount != ''){
                $marketers_id = $marketer->id;
                foreach($request->start_amount as $key=>$start_amount){
                    marketers_details::create([
                        'marketers_id'          => $marketers_id,
                        'start_amount'          => $request->start_amount[$key],
                        'end_amount'            => $request->end_amount[$key],
                        'marketers_commission'  => $request->marketers_commission[$key],
                    ]);
                }
            }
            return redirect()->route('marketers.index')->with('success', 'Marketer Added SuccessFully');
        }
        catch(\Exception $ex){
            return back()->withError($ex->getMessage());
        }
    }
    public function show(marketer $marketer)
    {
        //
    }

    public function edit(marketer $marketer)
    {
        return view('admin.people.marketers.edit', [
            'marketer' => $marketer,
            'marketers_details' => marketers_details::where('marketers_id', $marketer->id)->get(),
        ]);
    }

    public function update(Request $request, marketer $marketer){
        try{
            $marketerData = $request->only('marketers_name', 'marketers_mobile', 'details_id');
            marketer::find($marketer->id)->update($marketerData);

            if ($request->start_amount != [] || $request->start_amount != ''){
                foreach ($request->start_amount as $key =>$s){
                    if ($request->details_id[$key] != '') {
                        $update_data = array(
                            'marketers_id'          => $marketer->id,
                            'start_amount'          => $request->start_amount[$key],
                            'end_amount'            => $request->end_amount[$key],
                            'marketers_commission'  => $request->marketers_commission[$key]
                        );
                        marketers_details::where('id', $request->details_id[$key])->update($update_data);

                    } else{
                        marketers_details::create([
                            'marketers_id'          => $marketer->id,
                            'start_amount'          => $request->start_amount[$key],
                            'end_amount'            => $request->end_amount[$key],
                            'marketers_commission'  => $request->marketers_commission[$key],
                        ]);
                    }
                }
            }
            marketers_details::whereNotIn('id', $request->details_id)->delete();
            return redirect()->route('marketers.index')->with('success', 'Marketer Updated SuccessFully');
        }
        catch(\Exception $ex){
            dd($ex->getMessage());
            return back()->withError($ex->getMessage());
        }
    }

    public function destroy(marketer $marketer){
        try {
            marketers_details::where('marketers_id', $marketer->id)->delete();
            $marketer->delete();
            return back()->with(['message' => 'Customer Deleted Successfully!']);
        }
        catch (\Exception $ex) {
            return back()->with('error_message', 'This Marketer can not delete');
        }
    }
}
