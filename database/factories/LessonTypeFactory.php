<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\LessonType;
use Faker\Generator as Faker;

$factory->define(LessonType::class, function (Faker $faker) {
    return [
        // 'name' => $faker->words, 'description' => $faker->words, 'code' => $faker->words, 'icon' => $faker->words, 'active' => $faker->words
    ];
});
