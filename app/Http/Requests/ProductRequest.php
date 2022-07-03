<?php

namespace App\Http\Requests;

use App\Product;
use App\Services\Products\ProductService;
use App\Traits\FileSaver;
use Illuminate\Foundation\Http\FormRequest;
use DB;

class ProductRequest extends FormRequest
{
    private $service;

    use FileSaver;
    public function __construct(ProductService $productService)
    {
        $this->service = $productService;
    }
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        $productName = 'required|unique_with:products, product_name, fk_company_id';

        if (request()->method() == 'PUT') {
            $productName .= ',' . request()->segment(3);   // concat product id from request
        }
        return [
            'product_name'          => $productName,
            'fk_category_id'        => 'required|not_in:0',
            'fk_product_unit_id'    => 'required|not_in:0',
            'product_cost'          => 'required|numeric',
            'product_price'         => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required'         => 'Product name can not be empty',
            'product_name.unique_with'      => 'Product already exist for this company',
            'fk_category_id.required'       => 'Category can not be empty',
            'fk_product_unit_id.required'   => 'Unit can not be empty',
            'product_cost.required'         => 'Purchase price can not be empty',
            'product_price.required'        => 'Sales price can not be empty',
        ];
    }

    public function storeProduct()
    {
        DB::transaction(function () {
            // store data to products table
            $this->service->storeProduct();
            // store product rak
            $this->service->storeOrUpdateOpeningStock();
            // store product rak
            $this->service->storeOrUpdateProductRak();
            // store barcode
            $this->service->storeOrUpdateBarcode();
            // upload product image
            $this->service->uploadImage();
            // store product serials
            $this->service->storeProductSerials($this);

        });
    }

    public function updateProduct($product)
    {
        DB::transaction(function () use ($product) {

            $data = $this->service->updateProduct($product);

            // store opening stock
            $this->service->storeOrUpdateOpeningStock();

            // store product rak
            $this->service->storeOrUpdateProductRak();

            // update barcode
            $this->service->storeOrUpdateBarcode();

            // upload product image
            $this->service->uploadImage();
        });
    }
}
