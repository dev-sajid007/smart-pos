<?php

use Illuminate\Database\Seeder;
use App\Package;

class PackageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $packages = [
            ['fk_created_by' => 1,'fk_updated_by' => 1,'name' => 'Monthly','description' => 'monthly package','fk_billing_cycle_id' => 1,'trial_period' => 7],
            ['fk_created_by' => 1,'fk_updated_by' => 1,'name' => 'Quarterly','description' => 'quarterly package details','fk_billing_cycle_id' => 2,'trial_period' => 10],
            ['fk_created_by' => 1,'fk_updated_by' => 1,'name' => 'Bi-Annually','description' => 'bi-annually package details','fk_billing_cycle_id' => 3,'trial_period' => 15],
            ['fk_created_by' => 1,'fk_updated_by' => 1,'name' => 'Annually','description' => 'annually package details','fk_billing_cycle_id' => 4,'trial_period' => 20]
        ];

        foreach($packages as $package){
            Package::create($package);
        }

    }
}
