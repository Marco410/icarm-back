<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Course;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {
    return [
        // 'person_id' => $faker->words, 'name' => $faker->words, 'description' => $faker->words, 'link' => $faker->words, 'image' => $faker->words, 'duration' => $faker->words, 'active' => $faker->words
    ];
});
