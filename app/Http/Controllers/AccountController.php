<?php

namespace App\Http\Controllers;

use App\Account;
use App\Transaction;
use Illuminate\Http\Request;
use Auth;

class AccountController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $account = Account::withCount(['opening_balance as openingBalance' => function($q) {
                return $q->select(\DB::Raw('SUM(amount)'));
            }])->companies()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('account_no', 'LIKE', "%{$request->search}%")
                ->orWhere('account_name', 'LIKE', "%{$request->search}%")
                ->orWhere('branch_name', 'LIKE', "%{$request->search}%");
            })->paginate(30);

        return view('admin.account.accounts.index')->with('all_account', $account);
    }




    public function create()
    {
        return view('admin.account.accounts.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'account_no'    => 'required|unique:accounts',
            'account_name'  => 'required|max:160',
            'branch_name'   => 'required|max:160',
            'status'        => 'required'
        ]);

        if ($request->default_account) {
            $def_acct = 0;
            Account::where('default_account', 1)->where('fk_company_id', companyId())->update(['default_account' => 0]);
        } else {
            $def_acct = 0;
        }

        $account = new Account;
        $account->account_no = $request->account_no;
        $account->account_name = $request->account_name;
        $account->branch_name = $request->branch_name;
        $account->status = $request->status;
        $account->default_account = $def_acct;
        $account->fk_created_by = $request->user_id;
        $account->fk_updated_by = $request->user_id;
        $account->fk_company_id = $request->company_id;
        $account->save();


        if ($request->filled('opening_balance')) {
            $this->makeTransaction($account, $request);
        }
        if ($account) {
            return redirect()->route('accounts.index')->withSuccess('Account Created Successfully!');
        }
    }


    private function makeTransaction($account, $request)
    {
        $account->transaction()->create([
            'fk_account_id' => $account->id,
            'date'          => fdate($account->created_at, 'Y-m-d'),
            'amount'        => $request->opening_balance
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Account $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        $openingBalance = optional(Transaction::where('transactionable_id', $account->id)->where('transactionable_type', 'App\Account')->first())->amount ?? 0;

        return view('admin.account.accounts.edit', compact('account', 'openingBalance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Account $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'account_no' => 'required',
            'account_name' => 'required|max:160',
            'branch_name' => 'required|max:160'
        ]);

        if ($request->default_account) {
            $def_acct = 1;
            Account::where('default_account', 1)->where('fk_company_id', companyId())->update(['default_account' => 0]);
        }
        else {
            $def_acct = 0;
        }
        $stts = Account::where('id', $id)
            ->update(array('account_no' => $request->account_no,
                'account_name' => $request->account_name,
                'branch_name' => $request->branch_name,
                'default_account' => $def_acct));

        $account = Account::find($id);

        if ($account) {
            $transaction = Transaction::where('transactionable_id', $id)->where('transactionable_type', 'App\Account')->first();
            if($transaction) {
                $transaction->update([
                    'amount'        => $request->opening_balance
                ]);
            } else {
                $account->transaction()->create([
                    'fk_account_id' => $account->id,
                    'date'          => fdate($account->created_at, 'Y-m-d'),
                    'amount'        => $request->opening_balance ?? 0
                ]);
            }
            
            return redirect()->route('accounts.index')->with(['success' => 'Account Updated Successfully!']);
        }
    }

    public function destroy($id)
    {
        $stts = Account::where('id', $id)->delete($id);
        if ($stts) {
            return redirect()->route('accounts.index')->with(['message' => 'Account Deleted Successfully!']);
        }
    }

    public function status($id)
    {
        $stts = Account::findOrFail($id);
        if ($stts->status == 0) {
            Account::where('id', $id)->update(array('status' => 1));
        } elseif ($stts->status == 1) {
            Account::where('id', $id)->update(array('status' => 0));
        }
        return redirect()->route('accounts.index')->withSuccess('Status Updated Successfully!');
    }
}
