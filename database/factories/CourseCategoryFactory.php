<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\CourseCategory;
use Faker\Generator as Faker;

$factory->define(CourseCategory::class, function (Faker $faker) {
    return [
        // 'name' => $faker->words, 'description' => $faker->words, 'active' => $faker->words
    ];
});
