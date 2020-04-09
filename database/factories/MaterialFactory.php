<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Material;
use App\User;
use Faker\Generator as Faker;

$factory->define(Material::class, function (Faker $faker) {
    return [
        'nome' => $faker->name,
        'descricao' => $faker->paragraph,
        'emprestado' => $faker->boolean,
        'usuario_id' => factory(User::class),
        'horarioEmprestimo' => $faker->dateTime,
        'horarioDevolucao' => $faker->dateTime
    ];
});
