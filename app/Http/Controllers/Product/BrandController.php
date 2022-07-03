<?php

namespace App\Http\Controllers\Product;

use App\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $brands = Brand::companies()->userLog()->orderBy('id')->when($request->filled('name'), function ($q) use ($request) {
            $q->where('name', 'LIKE', "%{$request->name}%")->orWhere('code', 'LIKE', "%{$request->name}%");
        })->paginate(30);

        return view('admin.product.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.brands.create');
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
            'name'=>'required|max:160'
        ]);

        try {
            $max_code = Brand::max('code') ?? 1000;
            $brand = Brand::create([
                'name' => $request->name,
                'code' => 9999999,
                'company_id' => auth()->user()->fk_company_id,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id()
            ]);
            $brand->update(['code' => $max_code + 1]);
            return redirect()->route('brands.index')->withMessage('Brand created successfully');
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return redirect()->back()->withError($ex->getMessage());
        }
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
    public function edit(Brand $brand)
    {
        return view('admin.product.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $this->validate($request,[
            'name'=>'required|max:160|unique:brands,name,' . $brand->id
        ]);
        try {
            $brand->update($request->all());
            return redirect()->route('brands.index')->withSuccess('Brand updated successfully!');
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
    public function destroy(Brand $brand)
    {
        try {
            $brand->delete();
            return redirect()->back()->withSuccess('Brand deleted successfully!');
        } catch (\Exception $e){
            return redirect()->back()->withError('This brand is used in product');
        }
    }
}
