<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Customer::class, function (Faker $faker) {
    return [
        'fk_created_by' => 1,
        'fk_updated_by' => 1,
        'fk_company_id' => 1,
        'customer_code' => uniqid(\Illuminate\Support\Str::random(),true),
        'name' => $faker->name,
        'email' => $faker->unique()->email,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'customer_category_id' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
