<?php

use Faker\Generator as Faker;

$factory->define(App\Job::class, function (Faker $faker) {
    return [
        // 'customer_id' => $faker->,
        'employee_id' => rand(1,8),
        'estimate' => rand(1,10000),
        'est_note' => $faker->text($maxNbChars = 50),
        'note' => $faker->text($maxNbChars = 200),
        'appraisal' => $faker->boolean($chanceOfGettingTrue = 10),
        'due_date' => $faker->date($format = 'Y-m-d', $max = '+ 2 months', $startDate = '-30 years'),
        'completed_at' => $faker->optional($weight = 0.99)->date($format = 'Y-m-d', $max = 'now', $startDate = '-30 years'),
        'vital_date' => $faker->boolean($chanceOfGettingTrue = 20)
    ];
});
