<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;
use App\RolePermission;
use Faker\Factory as Faker;

class RolePermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker::create();
    	$role_ids = Role::pluck('id');
    	$permission_ids = Permission::pluck('id');
    	foreach($permission_ids as $permission_id){

			$faker_role_id = $faker->randomElement($role_ids);
			$faker_permissinon_id = $faker->randomElement($permission_ids);

    		$role_permission_exists = RolePermission::where([
    			'fk_role_id' => 1,
    			'fk_permission_id' => $permission_id
    		])->get();

			if(count($role_permission_exists)<1){
		    	RolePermission::create([
                    'fk_company_id' => 1,
                    'fk_created_by' => 1,
                    'fk_updated_by' => 1,
					'fk_role_id' => 1,
					'fk_permission_id' => $permission_id
	        	]);
		   }
    
    	}
    }
}
