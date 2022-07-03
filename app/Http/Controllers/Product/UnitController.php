<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\ProductUnit;
use Illuminate\Http\Request;
use Auth;

class UnitController extends Controller
{
    public function __construct(){
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $productUnits = ProductUnit::companies();

        if ($request->wantsJson()) {
            return $productUnits->where('name', 'LIKE', "%{$request->name}%") ->pluck('name', 'id');
        }

        return view('admin.product.units.index', [
            'productUnits' => $productUnits->userLog()->paginate(30)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.units.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:160|unique:product_units',
            'status' => 'required'
        ]);

        ProductUnit::create($request->all());

        return redirect()->route('units.index')->withSuccess('Product Unit Added Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductUnit  $productUnit
     * @return \Illuminate\Http\Response
     */
    public function show(ProductUnit $productUnit)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductUnit  $productUnit
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductUnit $unit)
    {
        $productUnit = $unit;
        return view('admin.product.units.edit', compact('productUnit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\ProductUnit $productUnit
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, ProductUnit $unit)
    {

        $this->validate($request, [
            'name' => 'required|max:160|unique:product_units,name,'.$unit->id,
            'status' => 'required'
        ]);

        $unit->update($request->all());

        return redirect()->route('units.index')->withSuccess('Product Unit Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductUnit $productUnit
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(ProductUnit $unit)
    {
        try {
            $unit->delete();
            return redirect()->back()->withSuccess('Product Unit Deleted Successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->withError('Product unit can not be deleted, because unit is used to product');
        }
    }
}
