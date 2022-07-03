<?php

use Illuminate\Database\Seeder;
use App\SubCategory;

class SubCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubCategory::create([
            'fk_company_id' => 1,
            'fk_created_by' => 1,
            'fk_updated_by' => 1,
            'fk_category_id' => 1,
            'sub_category_code' => 'smrt',
            'sub_category_name' => 'smartphone',
        ]);
    }
}
