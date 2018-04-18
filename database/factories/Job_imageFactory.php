<?php

use Faker\Generator as Faker;

$factory->define(App\Job_image::class, function (Faker $faker) {
    return [
        'note' => $faker->optional($weight = 0.5)->text($maxNbChars = 200),
        // 'image' => $faker->imageUrl($width = 1280, $height = 1024, 'cats')
        'image' => 'https://placekitten.com/1280/1024'
    ];
});
