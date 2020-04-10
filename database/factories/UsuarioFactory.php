<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Usuario;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Usuario::class, function (Faker $faker) {
    return [
        'nome' => $faker->name,
        'email' => $faker->unique()->safeEmail(),
        'password' => bcrypt('123456'),
        'role' => random_int(1, 3),
        'remember_token' => Str::random(10),
    ];
});
