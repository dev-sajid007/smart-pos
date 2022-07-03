<?php

namespace App\Http\Controllers;

use App\marketer;
use App\marketersledger;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MarketersledgerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->all() != []) {
            $marketers            = marketer::get();
            $marketersLedgerDatas = marketersledger::where('marketers_id', $request->marketers_id)
                ->when($request->filled('start_date') || $request->filled('end_date'), function ($q) use ($request) {
                    $q->whereBetween('created_at', [$request->start_date, $request->end_date]);
                })->paginate(100);
            return view('admin.people.marketers.ledger.index', compact('marketers', 'marketersLedgerDatas'));
        } else {
        return view('admin.people.marketers.ledger.index',[
            'marketers' => marketer::get(),
            'marketersLedgerDatas'=> marketersledger::paginate(100)
            ]);
        }
    }
    public function create($id)
    {
        return view('admin.people.marketers.ledger.create',['id' => $id]);
    }
    public function store(Request $request)
    {
        $marketer = marketer::where('id', $request->marketers_id);
        marketersledger::create([
            'marketers_id' => $request->marketers_id,
            'amount'       => $request->amount,
            'created_at'   => $request->created_at,
            'updated_at'   => Carbon::now(),
        ]);
        $balance = $marketer->first()->balance - $request->amount;
        $marketer->update([
            'balance' => $balance
        ]);
        return back()->withSuccess('Marketers Ledger Added Successfully');
    }
    public function edit($id)
    {
        return view('admin.people.marketers.ledger.edit', [
            'marketersledger' => marketersledger::where('id',$id)->first(),
            ]);
    }
    public function update(Request $request)
    {
        $marketersledger = marketersledger::where('id', $request->id);
        $amount = $request->amount;
        $chk = $amount - $marketersledger->first()->amount;

        $marketer = marketer::where('id', $request->marketers_id);
        if($chk < 0){
            $balance = $marketer->first()->balance - $chk;
            $marketer->update([
                'balance' => $balance
            ]);
        }
        else{
            $balance = $marketer->balance - $chk;
            $marketer->update([
                'balance' => $balance
            ]);
        }
        $marketersledger->update([
            'amount'      => $amount,
            'created_at'  => $request->created_at,
            'updated_at'  => date('Y-m-d h:i'),
        ]);
        return back()->withSuccess('Marketers Ledger Updated Successfully');
    }
}
