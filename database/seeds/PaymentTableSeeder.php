<?php

use Illuminate\Database\Seeder;
use App\Payment;

class PaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Payment::create([
            'fk_company_id' => 1,
            'fk_created_by' => 1,
            'fk_updated_by' => 1,
            'fk_account' => 1,
            'method_name' => 'bank',
            'description' => 'bank payment',
            'status' => '1',
        ]);   
    }
}
