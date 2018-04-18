<?php

use Faker\Generator as Faker;

$factory->define(App\Customer::class, function (Faker $faker) {
    return [
       'fname' => $faker->firstName,
       'lname' => $faker->lastName,
       'phone' => $faker->phoneNumber,
       'email' => $faker->unique()->safeEmail,
       'addr_st' => $faker->streetAddress,
       'addr_city' => $faker->city,
       'addr_prov' => $faker->stateAbbr,
       'addr_postal' => $faker->postcode,
       'addr_country' => $faker->country
    ];
});
