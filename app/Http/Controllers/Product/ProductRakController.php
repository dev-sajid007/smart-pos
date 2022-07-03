<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Rak;
use Illuminate\Http\Request;

class ProductRakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productRaks = Rak::companies()->userLog()->orderBy('id')->paginate(30);

        return view('admin.product.product-raks.index', compact('productRaks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.product-raks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([ 'name' => 'required|unique_with:raks, name, fk_company_id' ]);

        try {
            $brand = Rak::create([
                'name' => $request->name,
                'fk_company_id' => auth()->user()->fk_company_id,
                'fk_created_by' => auth()->id(),
                'fk_updated_by' => auth()->id()
            ]);

            return redirect()->route('product-raks.index')->withMessage('Product rak created successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->withError('Database error');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rak  $productRak
     * @return \Illuminate\Http\Response
     */
    public function edit(Rak $productRak)
    {
        return view('admin.product.product-raks.edit', compact('productRak'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rak  $productRak
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rak $productRak)
    {
        $request->validate([ 'name' => 'required|unique_with:raks, name, fk_company_id,' . $productRak->id ]);

        try {
            $productRak->update($request->all());
            return redirect()->route('product-raks.index')->withSuccess('Product Rak updated successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rak  $productRak
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rak $productRak)
    {
        try {
            $productRak->delete();
            return redirect()->back()->withSuccess('Product Rak deleted successfully!');
        } catch (\Exception $e){
            return redirect()->back()->withError('This Product Rak is used');
        }
    }
}
