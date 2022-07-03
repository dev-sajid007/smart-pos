<?php

namespace App\Http\Controllers\Product;

use App\Category;
use App\Imports\ImportProductCSV;
use App\Product;
use App\ProductUpload;
use App\Services\Products\ProductUploadService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Support\Facades\DB;

class ProductUploadController extends Controller
{
    private $service;
    private $productIds = [];

    public function __construct(ProductUploadService $productUploadService)
    {
        $this->service = $productUploadService;
    }

    public function uploadProductView()
    {
        return view('admin.product.product-uploads.uploads');
    }


    public function uploadProductStore(Request $request)
    {
        $request->validate(['product_csv' => 'required|mimes:csv,txt']);

        if ($request->file('product_csv')) {
            try {
                $data = Excel::import(new ImportProductCSV(), $request->file('product_csv'));
            } catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            return redirect()->route('product-confirm-list.index')->with('message', 'File Uploaded Successfully');
        } else {
            return redirect()->back()->withError('File Must be csv');
        }
    }


    // ################             CONFIRM PRODUCT         ##########################

    public function confirmList()
    {
        $products = ProductUpload::orderBy('id')->paginate(50);
        return view('admin.product.product-uploads.confirm-list', compact('products'));
    }



    public function confirmProductStore()
    {
        $products = ProductUpload::take(50)->get();


        try {
            foreach ($products as $key => $product) {
                $category_id    = $this->service->getUploadedProductCategory($product->fk_category_id);
                $unit_id        = $this->service->getUploadedProductUnit($product->fk_product_unit_id);
                $brand_id       = $product->brand_id == '' ? '' : $this->service->getUploadedProductBrand($product->brand_id);
                $generic_id     = $product->generic_id == '' ? null : $this->service->getUploadedProductGeneric($product->generic_id);

                $productData = array_merge($this->service->getUploadedProductData($product), [
                    'brand_id'              => $brand_id,
                    'fk_category_id'        => $category_id,
                    'fk_product_unit_id'    => $unit_id,
                    'generic_id'            => $generic_id,
                ]);



                DB::transaction(function () use ($product, $products, $productData) {
                    $newProduct = Product::where('product_name', $productData['product_name'])->first();

                    if (!$newProduct) {
                        $newProduct = Product::create($productData);
                    }

                    $this->service->storeProductStock($newProduct, $product->opening_quantity);

                    if ($product->product_rak_no != '') {
                        $this->service->getUploadedProductRakId($product->product_rak_no, $newProduct);
//                        $newProduct->update(['product_rak_id' => $this->service->getUploadedProductRakId($product->product_rak_no, $newProduct)]);
                    }
                    if ($product->barcode_id != '') {
                        $this->service->storeOrUpdateBarcode($newProduct->id, $product->barcode_id);
                    }

                    array_push($this->productIds, $product->id);
                });
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return redirect()->back()->withErrors($ex->getMessage());
        }

        if (count($this->productIds) > 0) {
            ProductUpload::destroy($this->productIds);
            return redirect()->back()->withMessage(count($this->productIds) . ' Products uploaded successfully');
        } else {
            return redirect()->back()->withErrors('No product uploaded, something error');
        }
    }

    // #####################  DELETE  #####################


    public function confirmProductDelete($id)
    {
        try {
            ProductUpload::destroy($id);
            return redirect()->back()->withMessage('Product Deleted Successfully!');
        }catch (\Exception $ex){
            return redirect()->back()->withError($ex->getMessage());
        }
    }

    public function confirmProductDeleteAll()
    {
        try {
            ProductUpload::truncate();
            return redirect()->back()->withMessage('All Product Deleted Successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->withError($ex->getMessage());
        }
    }
}
