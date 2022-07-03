<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductDamageRequest;
use App\Models\Inventory\ProductDamage;
use App\Product;
use App\ProductStock;
use Illuminate\Http\Request;

class ProductDamageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $damages = ProductDamage::withCount(['items as total_amount' => function($q) {
            $q->select(\DB::Raw('SUM(quantity * price)'));
        }])->companies()->userLog()->latest()->paginate();

        if (\request())

        return view('admin.product.damage.index', compact('damages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.damage.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductDamageRequest $request
     * @return void
     */
    public function store(ProductDamageRequest $request)
    {
        $request->persist();
        return back()->withSuccess('Damaged Products added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductDamage  $productDamage
     * @return \Illuminate\Http\Response
     */
    public function show(ProductDamage $productDamage)
    {
        $productDamage->load(['items.product', 'company']);

        return view('admin.product.damage.show', compact('productDamage'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param ProductDamage $productDamage
     * @return void
     */
    public function destroy(ProductDamage $productDamage)
    {
        try {
            foreach ($productDamage->items as $key => $item) {
                $this->decreaseWastageStock($item);
                $item->wastage()->delete();
                $item->delete();
            }
            $productDamage->delete();
            return back()->withSuccess('Damaged Products Deleted Successfully');
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage());
        }
    }



    public function decreaseWastageStock($item)
    {
        $stock = ProductStock::where('fk_product_id', $item->fk_product_id)->first();

        $quantity = $item->quantity;

        $stock->update([
            'available_quantity'    => $stock->available_quantity + $quantity,
            'wastage_quantity'      => $stock->wastage_quantity - $quantity
        ]);
    }
}
