<?php

use Illuminate\Database\Seeder;
use App\ProductUnit;

class ProductUnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductUnit::create([
            'fk_company_id' => 1,
            'fk_created_by' => 1,
            'fk_updated_by' => 1,
            'name' => 'piece',
            'description' => 'Quantity counted as piece',
            'status' => 1
        ]);
    }
}
