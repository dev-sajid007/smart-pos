<?php

use Illuminate\Database\Seeder;
use App\Product;


class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //foreach (range(2, 50) as $index)
        {

            Product::create([
                'fk_company_id' => 1,
                'fk_created_by' => 1,
                'fk_updated_by' => 1,
                'product_code' => 'product-1',
                'product_name' => 'samsung galaxy a10',
                'fk_category_id' => 1,
                'fk_sub_category_id' => 1,
                'fk_product_unit_id' => 1,
                'product_description' => 'Samsung Galaxy A10',
                'product_cost' => 20000,
                'product_price' => 25000,
                'product_alert_quantity' => 10,
                'product_reference' => 'a10',
                'supplier_id' => 1

            ]);

            Product::create([
                'fk_company_id' => 1,
                'fk_created_by' => 1,
                'fk_updated_by' => 1,
                'product_code' => 'product-2',
                'product_name' => 'Nokia 105',
                'fk_category_id' => 1,
                'fk_sub_category_id' => 1,
                'fk_product_unit_id' => 1,
                'product_description' => 'Nokia 105',
                'product_cost' => 1500,
                'product_price' => 1500,
                'product_alert_quantity' => 10,
                'product_reference' => 'a12',
                'supplier_id' => 1

            ]);

            Product::create([
                'fk_company_id' => 1,
                'fk_created_by' => 1,
                'fk_updated_by' => 1,
                'product_code' => 'product-3',
                'product_name' => 'I Phone X',
                'fk_category_id' => 1,
                'fk_sub_category_id' => 1,
                'fk_product_unit_id' => 1,
                'product_description' => 'I Phone X',
                'product_cost' => 100000,
                'product_price' => 100000,
                'product_alert_quantity' => 10,
                'product_reference' => 'a13',
                'supplier_id' => 1

            ]);
        }

    }
}
