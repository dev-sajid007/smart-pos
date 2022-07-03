<?php

use Illuminate\Database\Seeder;
use App\Party;

class PartyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Party::create([
            'fk_company_id' => 1,
            'fk_created_by' => 1,
            'fk_updated_by' => 1,
            'party_code' => 'party-1',
            'name' => 'party',
            'phone' => '01711223344',
            'email' => 'party@gmail.com',
            'address' => 'dhaka',
        ]);        
    }
}
