<?php

use Illuminate\Database\Seeder;
use App\SmsQuota;

class SmsQuotasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SmsQuota::create([
            'fk_company_id' => 1,
            'fk_created_by' => 1,
            'fk_updated_by' => 1,
            'fk_purchased_by' => 1,
            'quantity' => 50,
            'status' => 1
        ]);
    }
}
