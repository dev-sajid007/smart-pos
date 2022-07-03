<?php

use Illuminate\Database\Seeder;
use App\BillingCycle;

class BillingCyclesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $billing_cycles = [
            ['name' => 'monthly', 'days' => 30],
            ['name' => 'quarterly', 'days' => 90],
            ['name' => 'bi-annually', 'days' => 183],
            ['name' => 'annually', 'days' => 365]
        ];
        foreach($billing_cycles as $billing_cycle){
            BillingCycle::create($billing_cycle);
        }
       
    }
}
