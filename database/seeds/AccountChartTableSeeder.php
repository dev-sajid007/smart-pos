<?php

use Illuminate\Database\Seeder;
use App\AccountChart;

class AccountChartTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccountChart::create([
            'fk_company_id' => 1,
            'fk_created_by' => 1,
            'fk_updated_by' => 1,
            'head_type' => '1',
            'head_name' => 'Electricity Bill',
            'status' => '1',
        ]);
    }
}
