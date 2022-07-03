<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'fk_company_id' =>1,
            'fk_created_by' => 1,
            'fk_updated_by' => 1,
            'category_name' => 'Mobile',
        ]);
    }
}
