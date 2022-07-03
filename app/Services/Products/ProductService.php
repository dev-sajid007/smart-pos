<?php


namespace App\Services\Products;


use App\Barcode;
use App\Brand;
use App\Category;
use App\Customer;
use App\Generic;
use App\marketers_details;
use App\Product;
use App\ProductRak;
use App\ProductSerial;
use App\ProductStock;
use App\ProductUnit;
use App\Rak;
use App\SoftwareSetting;
use App\Supplier;
use App\taxRate;
use App\Traits\FileSaver;
use App\Warehouse;

class ProductService
{
    use FileSaver;

    public $product;
    public $data;
    public $request;

    // index
    public function getProducts()
    {
        $data['brands']         = Brand::companies()->pluck('name', 'id');
        $data['categories']     = Category::companies()->pluck('category_name', 'id');
        $data['productList']    = Product::pluck('product_name', 'id');
        $data['products']       = $this->filterProducts();

        return $data;
    }

    public function getProductsAjaxSearch()
    {
        $request = request();

        return Product::companies()->selectRaw('product_name as name, id')
            ->where('product_name', 'LIKE', "%{$request->name}%")
            ->take(10)
            ->pluck('name', 'id');
    }


    // create
    public function getFilterData()
    {
        $data['categories']     = Category::with('subcategories')->get();
        $data['brands']         = Brand::companies()->pluck('name', 'id');
        $data['generics']       = Generic::companies()->pluck('name', 'id');
        $data['productRaks']    = Rak::companies()->pluck('name', 'id');
        $data['warehouses']     = Warehouse::companies()->get();
        $data['tax_rates']      = taxRate::companies()->get();
        $data['product_units']  = ProductUnit::companies()->active()->get();
        $data['suppliers']      = Supplier::companies()->orderBy('id','desc')->pluck('name', 'id');
        $data['settings']       = SoftwareSetting::companies()->get();

        return $data;
    }

    public function storeProduct()
    {
        $storeRequestData = $this->getProductStoreData();
        // dd($storeRequestData);


        $this->product = Product::create($storeRequestData);

        if ($this->request->product_code == '') {
            $this->product->update([
                'product_code' => 'product-' . $this->product->id
            ]);
        }
    }
    public function updateProduct($product){
        $product->update($this->getProductStoreData());
        $this->product = $product;
    }

    public function getProductStoreData()
    {
        $this->request = $request = \request();
        $requestData = $request->only(['product_name', 'fk_category_id', 'fk_product_unit_id', 'product_cost', 'product_price', 'holesale_price', 'supplier_id', 'brand_id', 'generic_id', 'product_description', 'product_alert_quantity', 'product_reference', 'warranty_days', 'guarantee_days']);
        $requestData['expire_date']         = $this->getProductExpireDate();
        $requestData['tax']                 = $request->tax ?? 0;
        $requestData['discount']            = $request->discount ?? 0;
        $requestData['product_code']        = $request->product_code ?? time();
        $requestData['opening_quantity']    = $request->opening_quantity ?? 0;
        $requestData['has_serial']          = $request->has('has_serial') ? 1 : 0;

        if (SoftwareSetting::where('title', 'Product Subcategory')->where('options', 'yes')->first()) {
            $requestData['fk_sub_category_id']  = $request->fk_sub_category_id;
        }
        // dd($requestData);
        return $this->data = $requestData;
    }


    // opening stock store and update
    public function storeOrUpdateOpeningStock()
    {
        $request = \request();
        // for show room
        $productStock = $this->getProductStock();
        $qty=($request->serial_to) ? $request->serial_to : $request->opening_quantity;
        if ($productStock) {
            $this->updateProductStock($productStock, $qty);
        } else {
            $openingQuantity = $qty;
            $this->storeNewProductStock($openingQuantity, 0, $openingQuantity, 0); // for this reason opening quantity is available quantity
        }

        // for warehouse
        foreach ($request->warehouse_ids ?? [] as $key => $warehouse_id) {
            if ($request->opening_stocks[$key] != '') {
                $productStock = $this->getProductStock($warehouse_id);
                if ($productStock) {
                    $this->updateProductStock($productStock, $request->opening_stocks[$key]);
                } else {
                    $this->storeNewProductStock($request->opening_stocks[$key], 0, $request->opening_stocks[$key], 0, $warehouse_id); // for this reason opening quantity is available quantity
                }
            } else {
                $productStock = $this->getProductStock($warehouse_id);
                if ($productStock) {
                    $this->updateProductStock($productStock, 0);
                }
            }
        }
    }

    private function getProductStock($warehouse_id = null)
    {
        return $productStock = ProductStock::companies()->where('warehouse_id', $warehouse_id)->where('fk_product_id', $this->product->id)->first();
    }

    private function storeNewProductStock($opening_quantity, $purchased_quantity, $available_quantity, $sold_quantity, $warehouse_id = null)
    {
        $productStock = $this->product->product_stock()->create([
            'opening_quantity'      => $opening_quantity,
            'purchased_quantity'    => $purchased_quantity,
            'available_quantity'    => $available_quantity,
            'sold_quantity'         => $sold_quantity,
            'warehouse_id'          => $warehouse_id
        ]);
    }

    private function updateProductStock($productStock, $opening_quantity)
    {
        $productStock->update([
            'opening_quantity'      => $opening_quantity,
            'available_quantity'    => ($productStock->available_quantity - $productStock->opening_quantity) + $opening_quantity,
        ]);
    }

    public function uploadImage()
    {
        $images = $this->request->images;
        if($this->request->images){
            $this->uploadMultipleFileWithResize($images, $this->product, 'images', 'products');
        }else if($this->request->image)
        {
            $this->uploadFileWithResize($images, $this->product, 'image', 'products');
        }
    }

    public function storeOrUpdateBarcode()
    {
        if ($this->request->barcode) {
            // store or update
            Barcode::updateOrCreate(['product_id' => $this->product->id], ['barcode_number' => $this->request->barcode]);
        } else {
            // delete barcode
            Barcode::where('product_id', $this->product->id)->delete();
        }
    }

    //    private functions
    private function filterProducts()
    {
        $request = request();

        return Product::when ($request->filled('product_id'), function ($q) use ($request) {
                $q->whereId($request->product_id);
            })
            ->when ($request->filled('brand_id'), function ($q) use ($request) {
                $q->where('brand_id', $request->brand_id);
            })
            ->when ($request->filled('fk_category_id'), function ($q) use ($request) {
                $q->where('fk_category_id', $request->fk_category_id);
            })
            ->when ($request->filled('search'), function ($q) use ($request) {
                $q->where('product_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('product_code', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('product_cost', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('product_price', 'LIKE', '%' . $request->search . '%')
                    ->orWhereHas('product_unit', function ($q) use ($request) {
                        $q->where('name', 'LIKE', '%' . $request->search . '%');
                    })
                    ->orWhereHas('supplier', function ($q) use ($request) {
                        $q->where('name', 'LIKE', '%' . $request->search . '%');
                    });
            })
            ->withCount('sales_details')
            ->orderBy('sales_details_count', 'desc')
            ->paginate(30);
    }

    private function getProductExpireDate()
    {
        $setting = SoftwareSetting::companies()->where('title', 'Product Expire Date')->where('options', 'yes')->first();
        if ($setting) {
            return \request()->expire_date != '' ? fdate(str_replace('/', '-', \request()->expire_date), 'Y-m-d') : '';
        }
        return '';
    }


    public function storeOrUpdateProductRak(){
        if (request()->filled('rak_id')) {
            $product_rak = ProductRak::updateOrCreate([
                'fk_company_id' => auth()->user()->fk_company_id ?? 1,
                'product_id' => $this->product->id,
            ], ['rak_id' => $this->request->rak_id]);
        } else {
            ProductRak::where('id', $this->product->product_rak_id)->delete();
        }
    }

    // store product serials if serials exist
    public function storeProductSerials()
    {
        $request = \request();
        try {
            $productSerials = [];
            if ($request->filled('has_serial')) {

                $data = array_merge($this->data, ['has_serial' => 1]);
                if ($request->filled('is_common_generated')) {
                    $startFrom = (int)$request->serial_from;
                    $EndFrom   = (int)$request->serial_to;
                    $commonSerial = $request->common_serial;
                    for ($i = $startFrom; $i < $EndFrom; $i++)
                    {
                        $productSerials[] = $commonSerial . $i;
                    }
                } else {
                    $productSerials =  explode(",", str_replace("\r\n","",$request->product_serials) . "");
                }
            }

            // store serial if has serial
            if ($this->product && $request->filled('has_serial')) {
                $serialData = [];
                foreach ($productSerials as $key => $item) {
                    $serialData[] = [
                        'serial'      => $item,
                        'source'      => 'Opening',
                        'product_id'  => $this->product->id,
                        'created_by'  => auth()->id(),
                        'updated_by'  => auth()->id(),
                    ];
                }
                ProductSerial::insert($serialData);
                // dd($serialData);
                // ProductSerial::create($serialData);
            }
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
}
