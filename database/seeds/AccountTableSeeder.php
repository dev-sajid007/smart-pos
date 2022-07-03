<?php

use Illuminate\Database\Seeder;
use App\Account;

class AccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Account::create([
            'fk_company_id' => 1,
            'fk_created_by' => 1,
            'fk_updated_by' => 1,
            'account_no' => '123456',
            'account_name' => 'DBBL-123456',
            'branch_name' => 'dhanmondi',
            'status' => 1,
            'default_account' => 1
        ]);
    }
}
