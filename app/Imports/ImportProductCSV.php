<?php

namespace App\Imports;

use App\Product;
use App\ProductUpload;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportProductCSV implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
//        try {
            return new ProductUpload([
                'product_name'              => $row['product_name'],
                'product_code'              => $row['product_code'],
                'fk_category_id'            => $row['category_id'],
                'brand_id'                  => $row['brand_id'],
                'fk_product_unit_id'        => $row['unit_id'],
                'product_description'       => $row['description'],
                'product_cost'              => $row['purchase_price'] ?? 0,
                'product_price'             => $row['sale_price'] ?? 0,
                'tax'                       => $row['tax'],
                'opening_quantity'          => $row['showroom_opening_quantity'] ?? 0,
                'product_alert_quantity'    => $row['alert_quantity'] ?? 0,

                'expire_date'               => $row['expire_date'],
                'barcode_id'                => $row['barcode'],
                'generic_id'                => $row['generic'],
                'product_rak_no'            => $row['product_rak_no'],
            ]);
//        } catch (\Exception $ex) {
////            dd($row);
//        }

    }
}
