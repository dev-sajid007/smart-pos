<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Imports\ImportPurchaseCSV;
use App\Product;
use App\ProductStock;
use App\ProductUnit;
use App\Purchase;
use App\PurchaseUpload;
use App\Supplier;
use App\Warehouse;
use Illuminate\Http\Request;
use Excel;

class BulkPurchaseController extends Controller
{
    public function bulkPurchaseCreate(Request $request)
    {
        return view('admin.purchase.bulk-purchase.uploads');
    }

    public function bulkPurchaseStore(Request $request)
    {
        $request->validate(['purchase_csv' => 'required|mimes:csv,txt']);

        if ($request->file('purchase_csv')) {
            try {
                $purchase_upload_code = time();
                $data = Excel::import(new ImportPurchaseCSV($purchase_upload_code), $request->file('purchase_csv'));
            } catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            return redirect()->route('bulk-purchase-confirm-list')->with('message', 'File Uploaded Successfully');
        } else {
            return redirect()->back()->withError('File Must be csv');
        }
    }

    public function bulkPurchaseConfirmList()
    {
        $suppliers = Supplier::companies()->pluck('name', 'id');
        $warehouses = Warehouse::companies()->pluck('name', 'id');

        $purchases = PurchaseUpload::where('company_id', auth()->user()->fk_company_id)->paginate(20);

        $this->createUnmatchedData();

        return view('admin.purchase.bulk-purchase.confirm-list', compact('purchases', 'suppliers', 'warehouses'));
    }

    private function createUnmatchedData()
    {
        $purchases = PurchaseUpload::where('company_id', auth()->user()->fk_company_id)->take(20)->get();

        foreach ($purchases as $key => $purchase) {

            $this->firstOrCreateProduct($purchase);
        }
    }

    private function firstOrCreateBrand($purchase) {
        if ($purchase->brand_id == '') {
            return null;
        }
        $brand = Brand::where('id', $purchase->brand_id)->orWhere('name', $purchase->brand_id)->first();
        if ($brand) {
            return $brand->id;
        } else {
            return Brand::create([
                'name' => $purchase->brand_id,
                'code' => (Brand::max('id') + 1),
                'company_id' => auth()->user()->fk_company_id,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id()
            ])->id;
        }
    }

    private function firstOrCreateUnit($purchase) {
        if ($purchase->fk_product_unit_id == '') {
            return null;
        }
        $unit = ProductUnit::where('id', $purchase->fk_product_unit_id)->orWhere('name', $purchase->fk_product_unit_id)->first();
        if ($unit) {
            return $unit->id;
        } else {
            return ProductUnit::create([
                'name' => $purchase->fk_product_unit_id,
                'fk_company_id' => auth()->user()->fk_company_id
            ])->id;
        }
    }

    private function firstOrCreateCategory($purchase) {
        $category_name = $purchase->fk_category_id ?? 'Default Category';

        $category = Category::where('id', $category_name)->orWhere('category_name', $category_name)->first();

        if ($category) {
            return $category->id;
        } else {
            return Category::create([
                'category_name' => $category_name,
                'fk_company_id' => auth()->user()->fk_company_id,
                'fk_created_by' => auth()->id(),
                'fk_updated_by' => auth()->id()
            ])->id;
        }
    }

    private function firstOrCreateProduct($purchase)
    {
        $product = Product::where('product_name', $purchase->product_name)->first();

        if (!$product) {
            $data = [
                'fk_product_unit_id' => $this->firstOrCreateUnit($purchase),
                'brand_id' => $this->firstOrCreateBrand($purchase),
                'fk_category_id' => $this->firstOrCreateCategory($purchase)
            ];
            Product::create(array_merge([
                'product_name' => $purchase->product_name,
                'product_code' => $purchase->product_code,
            ], $data));
        }
    }


    // store confirm list
    public function bulkPurchaseConfirmListStore(Request $request) {

        $uploadedProducts = PurchaseUpload::take(20)->get();


        try {
            if ($uploadedProducts->count() > 0) {
                $purchase_upload_code = $uploadedProducts->first()->purchase_upload_code;
                $purchase = Purchase::where('purchase_upload_code', $purchase_upload_code)->first();

                if (!$purchase) {
                    $purchase = Purchase::create([
                        'purchase_date'     => $request->purchase_date,
                        'fk_supplier_id'    => $request->fk_supplier_id,
                        'warehouse_id'      => $request->warehouse_id,
                        'account_id'        => $request->account_id ?? 1,
                        'invoice_discount'  => $request->invoice_discount ?? 0,
                        'invoice_tax'       => $request->invoice_tax ?? 0,
                        'total_payable'     => $request->total_payable ?? 0,
                        'sub_total'         => $request->subtotal ?? 0,
                        'paid_amount'       => $request->total_paid_amount ?? 0,
                        'previous_due'      => $request->previous_due ?? 0,
                        'purchase_upload_code'  => $purchase_upload_code,
                    ]);
                }
                $purchase->update([ 'fk_status_id' => 1]);
            }

            $productIds = [];

            foreach ($uploadedProducts as $key => $product) {

                $newProduct = Product::where('product_name', $product->product_name)->first();

                $purchase->purchase_details()->create([
                    "fk_product_id"     => $newProduct->id,
                    "quantity"          => $product->quantity,
                    "unit_price"        => $product->product_cost,
                    "product_sub_total" => ($product->quantity * $product->product_cost),
                    'warehouse_id'      => $request->warehouse_id,
                ]);

                $productStock   = ProductStock::where('fk_company_id', auth()->user()->fk_company_id)->where('fk_product_id', $newProduct->id)->where('warehouse_id', $request->warehouse_id);
                $stockCount     = $productStock->count();

                if($stockCount) {
                    $firstStock = $productStock->first();

                    $firstStock->update([
                        "purchased_quantity" => $firstStock->purchased_quantity + $product->quantity,
                        "available_quantity" => $firstStock->available_quantity + $product->quantity,
                    ]);
                } else {
                    ProductStock::create([
                        'fk_product_id'         => $newProduct->id,
                        'opening_quantity'      => 0,
                        'purchased_quantity'    => $product->quantity,
                        'available_quantity'    => $product->quantity,
                        'sold_quantity'         => 0,
                        'warehouse_id'          => $request->warehouse_id
                    ]);
                }

                array_push($productIds, $product->id);
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return redirect()->back()->withErrors($ex->getMessage());
        }

        if (count($productIds) > 0) {
            PurchaseUpload::destroy($productIds);
            return redirect()->back()->withMessage(count($productIds) . ' Products purchased confirm');
        } else {
            return redirect()->back()->withErrors('No product purchased, something error');
        }
    }



    public function confirmPurchaseDelete($id)
    {
        try {
            PurchaseUpload::destroy($id);
            return redirect()->back()->withMessage('Purchase Deleted Successfully!');
        }catch (\Exception $ex){
            return redirect()->back()->withError($ex->getMessage());
        }
    }

    public function confirmPurchaseDeleteAll()
    {
        try {
            PurchaseUpload::truncate();
            return redirect()->back()->withMessage('All Product Deleted Successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->withError($ex->getMessage());
        }
    }
}
