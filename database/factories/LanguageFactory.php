<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Language;
use Faker\Generator as Faker;

$factory->define(Language::class, function (Faker $faker) {
    return [
        // 'name' => $faker->words, 'description' => $faker->words, 'active' => $faker->words
    ];
});
