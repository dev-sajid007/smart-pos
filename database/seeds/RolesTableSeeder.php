<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        //fk_company
        $data = [
            [
                'fk_company_id' => 1,
                'fk_created_by' => 1,
                'fk_updated_by' => 1,
                'name' => 'System Admin',
                'description' => 'All access'
            ],
        ];
        foreach($data as $role){
            Role::create($role);
        }
        
    }
}
