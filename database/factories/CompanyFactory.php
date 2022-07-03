<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name'=>'ABC Company',
        'phone'=>'12345678910',
        'email'=>'abc@gmail.com',
        'address'=>'Sample Address'
    ];
});
