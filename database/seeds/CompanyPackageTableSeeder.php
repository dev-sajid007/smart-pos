<?php

use Illuminate\Database\Seeder;
use App\CompanyPackage;

class CompanyPackageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyPackage::create([
            'fk_created_by' => 1,
            'fk_updated_by' => 1,
            'fk_company_id' => 1,
            'fk_billing_cycle_id' => 1,
            'trial_ends_at' => '2030-01-01',
            'ends_at' => '2030-01-01',
            'amount' => 0,
            'trial_period' => 10,
            'alert_before' => 10,
            'status' => 1
        ]);
    }
}
