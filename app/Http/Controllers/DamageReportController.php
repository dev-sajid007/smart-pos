<?php

namespace App\Http\Controllers;

use App\Models\Inventory\ProductDamage;
use App\Models\Inventory\ProductDamageItem;
use App\Supplier;
use Illuminate\Http\Request;

class DamageReportController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return $this->report();
    }

    private function report(){
        $request = request();
        $data['suppliers'] = Supplier::pluck('name', 'id'); 
        $data['supplier'] = $request->filled('supplier_id') ? Supplier::find($request->supplier_id):'';

        $data['product_damage_items'] = ProductDamageItem::with('product_damage')
                                                ->whereHas('product_damage', function($product_damage) use($request){
                                                    $product_damage->when($request->filled('from'), function($product_damage){
                                                        $product_damage->whereBetween('date', dateRange());
                                                    });
                                                })
                                                ->whereHas('product', function($product) use($request){
                                                    $product->when($request->filled('supplier_id'), function($product_supplier) use($request){
                                                        $product_supplier->where('supplier_id', $request->supplier_id);
                                                    });
                                                })
                                                ->when($request->filled('type'), function($productDamageItem) use($request){
                                                    $productDamageItem->where('type', $request->type);
                                                })
                                                ->get();

        // dd($data['product_damages']);
        return view('admin.reports.damage-report', $data);
    }
}
