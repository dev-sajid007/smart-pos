<?php

namespace App\Http\Controllers;

use App\Customer;
use App\CustomerCategory;
use Illuminate\Http\Request;

class CustomerCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = CustomerCategory::companies()->userLog()->orderByDesc('id')->paginate(30);
        return view('admin.customer-category.index', compact('categories'));
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
            'name' => 'required|unique:customer_categories,name'
        ],[
            'name.required' => 'Customer Category Field is required'
        ]);

        try {
            CustomerCategory::create($request->except('modal-type'));

            return redirect()->back()->withSuccess('Customer Category Create Successful');
        } catch (\Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerCategory $customerCategory)
    {
        $this->validate($request,[
            'name' => 'required|unique:customer_categories,name,' . $customerCategory->id
        ],[
            'name.required' => 'Customer Category Field is required'
        ]);

        $customerCategory->update($request->except('modal-type'));

        return redirect()->back()->withSuccess('Customer Category Update Successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (Customer::where('customer_category_id', $id)->first()) {
            return redirect()->back()->with('error','Customer Category Can not Delete, Because this id is use in another table.');
        }
        $customerCategory = CustomerCategory::findOrFail($id);
        $customerCategory->delete();

        return redirect()->back()->withSuccess('massage','Customer Category Delete Successful');
    }
}
