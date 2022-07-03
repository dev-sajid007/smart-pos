<?php

namespace App\Http\Controllers\Product;

use App\Barcode;
use App\CustomerPricing;
use App\Services\Products\ProductService;
use App\Http\Requests\ProductRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SoftwareSetting;
use App\Product;
use App\ProductRak;
use App\ProductSerial;
use App\ProductSerialSalesDetails;
use App\ProductStock;

class ProductController extends Controller
{
    private $service;

    public function __construct(ProductService $productService)
    {
        $this->service = $productService;
    }
    public function index(Request $request)
    {
        // return $data['product'] = Product::latest()->first()->images;
        // $product = json_encode($product);
        // return $product = explode(',', $data['product']);
        // return ($product);
        $data = $this->service->getProducts();
        //  $data;
        return view('admin.product.products.index', $data);
    }
    public function create()
    {
        $data = $this->service->getFilterData();
        return view('admin.product.products.create', $data);
    }


    public function store(ProductRequest $productRequest)
    {
        try {
            $productRequest->storeProduct();
        } catch (\Exception $ex) {
            dd($ex->getMessage());
            return back()->withError($ex->getMessage());
        }
        return redirect()->route('products.index')->withMessage('Product Created Successfully!');
    }

    private function getProductExpireDate()
    {
        $setting = SoftwareSetting::companies()->where('title', 'Product Expire Date')->where('options', 'yes')->first();
        if ($setting) {
            return \request()->expire_date != '' ? fdate(str_replace('/', '-', \request()->expire_date), 'Y-m-d') : '';
        }
        return '';
    }


    public function show($id)
    {
        $data['product']    = Product::with(['category'])->findOrFail($id);
        $data['settings']   = SoftwareSetting::companies()->get();

        return view('admin.product.products.show', $data);
    }


    public function edit(Product $product)
    {
        $data = $this->service->getFilterData();
        $data['product'] = $product->load('warehouse_stocks', 'product_rak');

        return view('admin.product.products.edit', $data);
    }


    public function update(ProductRequest $productRequest, Product $product)
    {
        try {
            $productRequest->updateProduct($product);

            return redirect()->route('products.index')->withMessage('Product Updated Successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->withError($ex->getMessage());
        }
    }


    public function destroy(Product $product)
    {
        try {
            \DB::transaction(function () use ($product) {
                Barcode::where('product_id', $product->id)->delete();
                ProductRak::where('product_id', $product->id)->delete();
                CustomerPricing::where('product_id', $product->id)->delete();
                ProductStock::where('fk_product_id', $product->id)->delete();
                ProductSerial::where('product_id', $product->id)->delete();
                $product->delete();
            });
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return back()->with('error', 'This Product is use in another table, You can not delete it!');
        }
        return redirect()->back()->with(['success' => 'Product Deleted Successfully!']);
    }

    public function getProductBySerial(Request $request)
    {
        $product = Product::whereHas('product_serials', function ($q) use ($request) {
            $q->where('serial', $request->serial)->where('is_sold', 0);
        })->with(['product_serials' => function ($q) use ($request) {
            $q->where('serial', $request->serial)->where('is_sold', 0);
        }])->first();

        if ($product) {
            $data['id']     = $product->id;
            $data['name']   = $product->product_name;
            $data['code']   = $product->product_code;
            $data['price']  = $product->product_price;
            $data['serial'] = $request->serial;

            return $data;
        }
    }

    public function SerialProduct(Request $request)
    {
        $data['productList']  = Product::pluck('product_name', 'id');
        $data['SerialList']   = ProductSerial::with('sales')->when($request->filled('search'), function ($query) use ($request) {
            return $query->where('serial', 'LIKE', "%{$request->search}%");
        })
            ->when($request->filled('product_id'), function ($query) use ($request) {
                return $query->where('product_id', $request->product_id);
            })
            ->with(['Product' => function ($query) use ($request) {
                return $query->select('id', 'product_name', 'product_cost', 'product_price', 'warranty_days');
            }])
            // ->where('is_sold', 1)
            ->paginate(25);
            // return $data;
        return view('admin.product.products.serial_products', $data);
    }

    public function UpdateSerial(Request $request)
    {
        try {
             $serial = ProductSerial::where('serial', $request->serial)->first();
             $serial->update(['is_sold'=> '0']);
            ProductSerialSalesDetails::where('product_serial_id', $serial->id)->delete();
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
        return response()->json([
            'status' => true,
            'message' => 'delete success',
        ]);
    }
    // public function get_product($id)
    // {
    //     return Product::findOrFail($id);
    // }

    // public function getProduct(Product $product, Request $request)
    // {
    //     if ($request->ajax())
    //     {
    //         return $product;
    //     }
    // }



    // function get_json_list(Request $request)
    // {
    //     if($request->ajax())
    //     {
    //         $product_list = Product::companies()->pluck('product_name', 'id');
    //         return response()->json($product_list);
    //     }
    // }

    // function get_product_by_id(Request $request)
    // {
    //     if($request->ajax())
    //     {
    //         $product_info = Product::companies()->with('product_stock', 'product_unit')->where('id', $request->id)->get();
    //         return response()->json($product_info);
    //     }
    // }



    public function saleableProducts(Request $request)
    {
        return Product::with('product_stock')
            ->selectRaw('product_name as name, id')
            ->where('product_name', 'LIKE', "%{$request->name}%")
            ->take(10)
            ->pluck('name', 'id');
    }


    public function editProductPrice(Request $request)
    {
        $data = $this->service->getProducts();
        return view('admin.product.update-product-pricing.index', $data);
    }

    public function updateProductPrice(Request $request)
    {
        foreach ($request->product_ids as $key => $product_id) {
            try {
                Product::where('id', $product_id)->update([
                    'product_cost'  => $request->product_costs[$key],
                    'product_price' => $request->product_prices[$key],
                ]);
            } catch (\Exception $ex) {
            }
        }
        return redirect()->back()->withSuccess('Product Prices Successfully Updated');
    }
}
