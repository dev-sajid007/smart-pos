<?php

use Illuminate\Database\Seeder;

class GlAccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accounts = ['Assets', 'Liabilities', 'Income', 'Costs'];
        $auth_id = \App\User::first()->id;

        foreach ($accounts as $key => $account) {
            \App\GlAccount::create([
                'name' => $account,
                'created_by' => $auth_id,
                'updated_by' => $auth_id,
            ]);
        }
    }
}
