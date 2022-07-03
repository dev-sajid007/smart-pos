<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Warehouse;
use Illuminate\Http\Request;
use Auth;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouses = Warehouse::companies()->get();
        return view('admin.product.warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.warehouses.create');
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
            'name' => 'required|unique:warehouses,name',
        ]);

        try {
            Warehouse::create([
                'name' => $request->name,
                'fk_company_id' => auth()->user()->fk_company_id,
                'fk_created_by' => auth()->id()
            ]);

            return redirect()->route('warehouses.index')->with(['success' => 'Warehouse Created Successfully!']);
        } catch (\Exception $ex) {
            return back()->with(['error' => $ex->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function edit(Warehouse $warehouse)
    {
        return view('admin.product.warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|unique:warehouses,name,' . $warehouse->id,
        ]);

        try {
            $warehouse->update([
                'name' => $request->name,
                'fk_updated_by' => auth()->id()
            ]);

            return redirect()->route('warehouses.index')->with(['success' => 'Warehouse Updated Successfully!']);
        } catch (\Exception $ex) {
            return back()->with(['error' => $ex->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Warehouse::destroy($id);
            return redirect()->route('warehouses.index')->with(['success' => 'Warehouse Deleted Successfully!']);
        } catch (\Exception $ex) {
            return back()->with(['error' => $ex->getMessage()]);
        }
    }
}
