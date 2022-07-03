<?php

use Illuminate\Database\Seeder;
use App\Supplier;

class SupplierTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::create([
            'fk_company_id' => 1,
            'fk_created_by' => 1,
            'fk_updated_by' => 1,
            'supplier_code' => 'supplier-1',
            'name' => 'supplier',
            'email' => 'supplier@gmail.com',
            'phone' => '01711223344',
            'address' => 'Dhaka'
        ]);
    }
}
