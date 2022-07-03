<?php

namespace App\Http\Controllers;

use App\Liability;
use Illuminate\Http\Request;

class LiabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $liabilities = Liability::latest()->get();
        return view('admin.account.liabilities.index', compact('liabilities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.account.liabilities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $liability = Liability::create($request->all());
        $liability->update([
            'current_balance' => $liability->opening
        ]);
        return redirect()->route('liabilities.index')->withSuccess('New liability added');
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
    public function edit(Liability $liability)
    {
        return view('admin.account.liabilities.edit', compact('liability'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Liability $liability)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $liability->update($request->all());
        $liability->update([
            'current_balance' => $liability->opening
        ]);
        return redirect()->route('liabilities.index')->withSuccess('liability edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Liability::destroy($id);

        return redirect()->back()->withSuccess('Liability deleted');
    }
}
