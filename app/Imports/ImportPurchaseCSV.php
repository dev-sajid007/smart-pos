<?php

namespace App\Imports;

use App\Product;
use App\ProductUpload;
use App\PurchaseUpload;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportPurchaseCSV implements ToModel, WithHeadingRow
{
    private $purchase_upload_code;

    public function __construct($purchase_upload_code)
    {
        $this->purchase_upload_code = $purchase_upload_code;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new PurchaseUpload([
            'product_name'              => $row['product_name'],
            'product_code'              => $row['product_code'],
            'fk_category_id'            => $row['category_id'],
            'brand_id'                  => $row['brand_id'],
            'fk_product_unit_id'        => $row['unit_id'],
            'product_cost'              => $row['purchase_price'] ?? 0,
            'quantity'                  => $row['quantity'] ?? 0,
            'purchase_upload_code'      => $this->purchase_upload_code,
        ]);
    }
}
