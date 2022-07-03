<?php

namespace App\Http\Controllers\Product;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class CategoryController extends Controller
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


    public function index(Request $request)
    {
        $categories = Category::companies();

        if ($request->wantsJson()){
            return $categories->selectRaw('category_name as name, id')
                ->where('category_name', 'LIKE', "%{$request->name}%")
                ->pluck('name', 'id');
        }
        $categories = $categories->userLog()->paginate(30);
        return view('admin.product.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['category_name' => 'required']);

        $category = Category::create([
            'category_name' => $request->category_name,
            'fk_created_by' => auth()->id(),
            'fk_updated_by' => auth()->id(),
            'fk_company_id' => auth()->user()->fk_company_id
        ]);

        if($category) {
            return redirect()->route('categories.index')->withSuccess('Category Created Successfully!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.product.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->validate($request,[
            'category_name'=>'required|max:160'
        ]);

        $category->update([
            'category_name' => $request->category_name,
            'fk_updated_by' => auth()->id()
        ]);
        return redirect()->route('categories.index')->withSuccess('Category Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->back()->withSuccess('Category deleted successfully!');
        } catch (\Exception $e){
            return redirect()->back()->withError('This Category is used in product');
        }
    }


    public function get_json_list(Request $request)
    {
        if($request->ajax())
        {
            $categories = Category::where('fk_company_id', Auth::user()->fk_company_id)->pluck('category_name', 'id');
            return response()->json($categories);
        }
    }
}
