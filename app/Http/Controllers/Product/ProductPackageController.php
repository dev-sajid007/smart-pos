<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductPackage;
use App\ProductPackageDetail;
use Illuminate\Http\Request;

class ProductPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productPackages = ProductPackage::orderBy('name')->paginate(30);
        return view('admin.product.product-packages.index', compact('productPackages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::get();
        return view('admin.product.product-packages.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $productPackage = ProductPackage::create([
            'name' => $request->name,
            'price' => 0
        ]);

        foreach ($request->product_ids as $key => $product_id) {
            $productPackage->package_details()->create([
                'product_id' => $product_id,
                'quantity' => $request->quantities[$key],
                'price' => $request->unit_cost[$key]
            ]);
        }
        $total = $productPackage->package_details->sum('amount');
        $productPackage->update([ 'price' => $total ]);

        return redirect()->route('product-packages.show', $productPackage->id)->withSuccess('Product Package Created Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductPackage  $productPackage
     * @return \Illuminate\Http\Response
     */
    public function show(ProductPackage $productPackage)
    {
        return view('admin.product.product-packages.show', compact('productPackage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductPackage  $productPackage
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductPackage $productPackage)
    {
        $products = Product::get();
        return view('admin.product.product-packages.edit', compact('productPackage', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductPackage  $productPackage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductPackage $productPackage)
    {

        $detail_ids = array_filter($request->detail_ids);

        ProductPackageDetail::where('product_package_id', $productPackage->id)->whereNotIn('id', $detail_ids)->delete();

        foreach ($request->product_ids as $key => $product_id) {

            if ($request->product_ids[$key] != null) {
                $productPackage->package_details()->create([
                    'product_id' => $product_id,
                    'quantity' => $request->quantities[$key],
                    'price' => $request->unit_cost[$key]
                ]);
            } else {
                ProductPackageDetail::find($request->product_ids[$key])->update([
                    'quantity' => $request->quantities[$key],
                    'price' => $request->unit_cost[$key]
                ]);
            }
        }
        $total = $productPackage->package_details->sum('amount');
        $productPackage->update([ 'price' => $total ]);
        
        return redirect()->route('product-packages.show', $productPackage->id)->withSuccess('Product Package Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductPackage  $productPackage
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductPackage $productPackage)
    {
        $productPackage->delete();
        return redirect()->back()->withSuccess('Product Package Deleted Successfully');
    }
}
