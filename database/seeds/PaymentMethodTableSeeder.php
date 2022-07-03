<?php

use App\Payment;
use App\User;
use App\Company;
use Illuminate\Database\Seeder;

class PaymentMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = User::first()->id;

        $data = [
            'status'        => 1,
            'fk_created_by' => $user_id,
            'fk_updated_by' => $user_id,
            'fk_company_id' => Company::first()->id
        ];

        $methodNames = ['Cash', 'Check', 'Mobile Banking'];
        foreach ($methodNames as $key => $name) {
            $peymentMethod = Payment::firstOrCreate(['method_name' => $name], $data);
        }
    }
}
