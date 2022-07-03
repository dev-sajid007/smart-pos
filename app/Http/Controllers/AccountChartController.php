<?php

namespace App\Http\Controllers;

use App\AccountChart;
use App\GlAccount;
use Illuminate\Http\Request;
use Auth;

class AccountChartController extends Controller
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
    public function index(Request $request)
    {
        $account_chart = AccountChart::with('voucher_account_charts')
                ->companies()->userLog()->with('gl_account')
                ->when($request->filled('search'), function ($q) use ($request) {
                    $q->where('head_name', 'LIKE', "%{$request->search}%");
                });

        if($request->ajax()) {
            $headType = $request->type == 'debit' ? '1' : '0';
            $charts = $account_chart
                ->selectRaw('head_name as name, id')
                ->where('head_type', $headType)
                ->where('head_name', 'LIKE', "%{$request->name}%")
                ->take(15)
                ->pluck('name', 'id');
            return response($charts, 200);
        }



        return view('admin.account.charts.index',[
            'account_chart'=> $account_chart->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gl_accounts = GlAccount::pluck('name', 'id');
        return view('admin.account.charts.create', compact('gl_accounts'));
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
            'gl_account_id' => 'required',
            'head_type'     => 'required',
            'head_name'     => 'required|max:160',
            'status'        => 'required'
        ]);

        $head = new AccountChart;

        $head->gl_account_id    = $request->gl_account_id;
        $head->head_type        = $request->head_type;
        $head->head_name        = $request->head_name;
        $head->status           = $request->status;
        $head->fk_created_by    = $request->user_id;
        $head->fk_updated_by    = $request->user_id;
        $head->fk_company_id    = $request->company_id;

        $head->save();

        if($head) {
            return redirect()->route('accounts-charts.index')->withSuccess('Head Created Successfully!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AccountChart  $accountChart
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account_chart = AccountChart::findOrFail($id);
        return view('admin.account.charts.show', ['account_chart' => $account_chart]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AccountChart  $accountChart
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $account_charts = AccountChart::findOrFail($id);
        $gl_accounts = GlAccount::companies()->orderBy('id')->pluck('name', 'id');

        return view('admin.account.charts.edit', compact('account_charts', 'gl_accounts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AccountChart  $accountChart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $accountChart)
    {
         $attributes = $request->validate([
            'gl_account_id' => 'required',
            'head_type'     => 'required',
            'head_name'     => 'required'
        ]);

         AccountChart::find($accountChart)->update($attributes);

         return redirect()->route('accounts-charts.index')->with(['success'=>'Account Chart Updated Successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AccountChart  $accountChart
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $stts = AccountChart::where('id',$id)->delete($id);
        }catch (\Exception $e){
            return redirect()->back()->with('error_message','This Chart of account is use in another table you can not delete it!');
        }
            return redirect()->route('accounts-charts.index')->with(['message'=>'Account Chart Deleted Successfuly!']);

    }

     public function status($id){
        $stts=AccountChart::findOrFail($id);
        if($stts->status==0){
            AccountChart::where('id',$id)->update(array('status' => 1));
        } elseif($stts->status==1){
            AccountChart::where('id',$id)->update(array('status' => 0));
        }
        return redirect()->route('accounts-charts.index')->with(['message'=>'Status Updated Successfuly!']);
    }
}
