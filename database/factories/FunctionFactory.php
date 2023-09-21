<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\FunctionModel;
use Faker\Generator as Faker;

$factory->define(FunctionModel::class, function (Faker $faker) {
    return [
        // 'function_id' => $faker->words, 'label' => $faker->words, 'name' => $faker->words, 'description' => $faker->words, 'code' => $faker->words, 'insert' => $faker->words, 'update' => $faker->words, 'delete' => $faker->words, 'menu' => $faker->words, 'icon' => $faker->words, 'link' => $faker->words, 'order' => $faker->words, 'comment' => $faker->words, 'active' => $faker->words
    ];
});
