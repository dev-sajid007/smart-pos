<?php

namespace App\Http\Controllers\Product;

use App\Generic;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GenericController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $generics = Generic::companies()->userLog()->orderBy('id')->paginate(30);

        return view('admin.product.generics.index', compact('generics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.generics.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([ 'name' => 'required|unique_with:generics, name, fk_company_id' ]);

        try {
            $brand = Generic::create([
                'name'          => $request->name,
                'fk_company_id' => auth()->user()->fk_company_id,
                'fk_created_by' => auth()->id(),
                'fk_updated_by' => auth()->id()
            ]);

            return redirect()->route('generics.index')->withMessage('Generic created successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->withError('Database error');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Generic  $generic
     * @return \Illuminate\Http\Response
     */
    public function edit(Generic $generic)
    {
        return view('admin.product.generics.edit', compact('generic'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Generic  $generic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Generic $generic)
    {
        $request->validate([ 'name' => 'required|unique_with:generics, name, fk_company_id,' . $generic->id ]);

        try {
            $generic->update($request->all());
            return redirect()->route('generics.index')->withSuccess('Generic updated successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Generic  $generic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Generic $generic)
    {
        try {
            $generic->delete();
            return redirect()->back()->withSuccess('Generic deleted successfully!');
        } catch (\Exception $e){
            return redirect()->back()->withError('This Generic is used in another');
        }
    }
}
