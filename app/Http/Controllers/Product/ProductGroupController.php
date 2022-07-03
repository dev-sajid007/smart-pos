<?php

namespace App\Http\Controllers\Product;

use App\Group;
use App\ProductGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productGroups = ProductGroup::where('company_id', companyId())->orderBy('id')->paginate(30);
        return view('admin.product.product-groups.index', compact('productGroups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.product-groups.create');
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
            'name'=>'required|max:160|unique:product_groups,name'
        ]);

        try {
            $productGroup = ProductGroup::create([
                'name' => $request->name,
                'code' => 1,
                'company_id' => auth()->user()->fk_company_id,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id()
            ]);
            $productGroup->update(['code' => ($productGroup->id + 100)]);
            return redirect()->route('product-groups.index')->withMessage('Product group created successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->withError('Database error');
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
    public function edit(ProductGroup $productGroup)
    {
        return view('admin.product.product-groups.edit', compact('productGroup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductGroup $productGroup)
    {
        $this->validate($request,[
            'name'=>'required|max:160|unique:product_groups,name,' . $productGroup->id
        ]);
        try {
            $productGroup->update($request->all());
            return redirect()->route('product-groups.index')->withSuccess('Product group updated successfully!');
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
    public function destroy(ProductGroup $productGroup)
    {
        try {
            $productGroup->delete();
            return redirect()->back()->withSuccess('Product group deleted successfully!');
        } catch (\Exception $e){
            return redirect()->back()->withError('This group is used in product');
        }
    }
}
