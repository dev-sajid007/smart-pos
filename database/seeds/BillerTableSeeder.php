<?php

use Illuminate\Database\Seeder;
use App\Biller;

class BillerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Biller::create([
            'fk_company_id' => 1,
            'fk_created_by' => 1,
            'fk_updated_by' => 1,
            'biller_code' => 'biller-1',
            'name' => 'biller',
            'email' => 'biller@gmail.com',
            'phone' => '01711223344',
            'address' => 'Dhaka'
        ]);
    }
}
