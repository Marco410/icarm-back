<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\FunctionProfile;
use Faker\Generator as Faker;

$factory->define(FunctionProfile::class, function (Faker $faker) {
    return [
        // 'function_id' => $faker->words, 'profile_id' => $faker->words, 'insert' => $faker->words, 'update' => $faker->words, 'delete' => $faker->words
    ];
});
