<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Module;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker::create();
    	$module_array_raw = [
    		'Sales', ['General Accounts', 1 ], 'Purchase', 'Quotation', 'Product', ['Account Configuration', 1], 'Supplier',
    		'Customer', ['Reports', 1], ['Settings', 1], 'User', ['Sms Send', 1],
            ['Dashboard', 1]
    	];

        foreach($module_array_raw as $module){
        	if(is_array($module)){
	        	Module::create([
                    'fk_company_id' =>  1,
					'name' => $module[0],
					'no_general' => $module[1]
	        	]);
        	} else {
        		Module::create([
                    'fk_company_id' => 1,
        			'name' => $module
        		]);
        	}
        }
    }
}
