<?php

use Illuminate\Database\Seeder;
use App\UserRole;

class SystemAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserRole::create([
            'fk_company_id' => 1,
            'fk_created_by' => 1,
            'fk_updated_by' => 1,
            'user_id' => 1,
            'role_id' => 1,
        ]);
    }
}
