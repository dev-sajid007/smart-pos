<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\User;
use App\Role;
use App\UserRole;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $users = User::pluck('id');
        $roles = Role::pluck('id');

        $i=0;
        while($i<count($roles)){
            $userId = $faker->randomElement($users);
            $roleId = $faker->randomElement($roles);
            $userRole = UserRole::where(['user_id'=> $userId, 'role_id'=> $roleId])->get();
            if(count($userRole)==0){
                UserRole::create([
                    'fk_company_id' => 1,
                    'fk_created_by' => 1,
                    'fk_updated_by' => 1,
                    'user_id' => $userId,
                    'role_id' => $roleId
                ]);
                $i++;
            }
        }
    }
}
