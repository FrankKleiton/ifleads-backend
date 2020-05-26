<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Material;
use Faker\Generator as Faker;

$factory->define(Material::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph,
        'amount' => $faker->randomDigit,
        'returner_registration_mark' => $faker->numerify('####################'),
        'tooker_registration_mark' => $faker->numerify('####################')
    ];
});
