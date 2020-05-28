<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Loan;
use App\User;
use App\Material;
use Faker\Generator as Faker;

$factory->define(Loan::class, function (Faker $faker) {
    $user = factory(User::class)->create();
    $material = factory(Material::class)->create();
    return [
        'user_id' => $user->id,
        'material_id' => $material->id,
        'tooker_id' => $faker->numerify('####################'),
        'loan_time' => $faker->iso8601()
    ];
});
