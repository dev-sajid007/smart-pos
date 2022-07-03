<?php

use Illuminate\Database\Seeder;

class CustomerCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\CustomerCategory::insert([
            'company_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'name' => 'New Customer',
            'description' => 'This is for new customer',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
