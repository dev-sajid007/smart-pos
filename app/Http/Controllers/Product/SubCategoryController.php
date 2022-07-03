<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SubCategory;
use App\Category;
use DB;

class SubCategoryController extends Controller
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
    public function index()
    {
        $subcategories = SubCategory::companies()->paginate(30);


        return view('admin.product.sub-categories.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('category_name')->pluck('category_name', 'id');
        return view('admin.product.sub-categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'sub_category_name' => 'required|max:160'
        ]);

        $sub_category = new subCategory;

        $sub_category->sub_category_name = $request->sub_category_name;
        $sub_category->fk_created_by = auth()->id();
        $sub_category->fk_updated_by = auth()->id();
        $sub_category->fk_company_id = companyId();
        $sub_category->fk_category_id = $request->fk_category_id;

        $sub_category->save();

        if ($sub_category) {
            return \redirect()->back()->withSuccess('Sub Category Created Successfully!');
        }
    }

    public function show($id)
    {
        return response()->json(SubCategory::where('fk_category_id', $id)->get() ?? collect([]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\subCategory $subCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(SubCategory $subcategory)
    {

        $category = Category::all();

        return view('admin.product.sub-categories.edit', [
            'sub_category' => $subcategory
        ])->with('category', $category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\subCategory $subCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubCategory $subcategory)
    {

        try {
            $subcategory->update([
                'sub_category_name' => $request->sub_category_name,
                'fk_category_id' => $request->fk_category_id,
                'fk_updated_by' => auth()->id()
            ]);
            return redirect()->back()->withSuccess('Subcategory deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withError('This Sub Category use in another table you can not to delete it');
        }
        $this->validate($request, [
            'sub_category_name' => 'required|max:160'
        ]);

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\subCategory $subCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            SubCategory::destroy($id);
            return redirect()->back()->withSuccess('Subcategory deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withError('This Sub Category use in another table you can not to delete it');
        }
    }
}
