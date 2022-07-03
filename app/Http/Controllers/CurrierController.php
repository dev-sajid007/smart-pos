<?php

namespace App\Http\Controllers;

use App\Currier;
use App\Customer;
use Illuminate\Http\Request;

class CurrierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $curriers = Currier::companies()->orderBy('name')->paginate(30);
        return view('admin.curriers.index', compact('curriers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.curriers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique_with:curriers, name, fk_company_id']);
        try {
            Currier::create(array_merge(['fk_company_id' => auth()->user()->fk_company_id], $request->all()));
            return redirect()->route('curriers.index')->withSuccess('Currier create successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Currier $currier)
    {
        return view('admin.curriers.edit', compact('currier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currier $currier)
    {
        $request->validate(['name' => 'required|unique_with:curriers, name, fk_company_id,' . $currier->id]);
        try {
            $currier->update($request->all());
            return redirect()->route('curriers.index')->withSuccess('Currier updated successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->withError($ex->getMessage());
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
        try {
            Currier::destroy($id);
            return redirect()->back()->withSuccess('Currier created successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->withError($ex->getMessage());
        }
    }
}
