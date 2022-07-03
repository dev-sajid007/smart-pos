<?php

namespace App\Http\Controllers;

use App\GlAccount;
use Illuminate\Http\Request;

class GlAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $accounts = GlAccount::when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%");
            })->paginate(30);

        return view('admin.account.gl-accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.account.gl-accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required:unique:gl_accounts,name']);
        $glAccount = GlAccount::create([
            'name' => $request->name,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'fk_company_id' => auth()->user()->fk_company_id
        ]);
        return back()->withSuccess('Account Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $account = GlAccount::findOrFail($id);
        return view('admin.account.gl-accounts.edit', ['account' => $account]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:gl_accounts,name,' . $id,
        ]);

        $account = GlAccount::find($id)->update(['name' => $request->name, 'updated_by' => auth()->id()]);
        return back()->withSuccess('Account Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            GlAccount::destroy($id);
            return back()->withSuccess('Account deleted successfully');
        } catch (\Exception $ex) {
            return back()->withError('This Account Already in used');
        }
    }
}
