<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\CourseSetting;
use Faker\Generator as Faker;

$factory->define(CourseSetting::class, function (Faker $faker) {
    return [
        // 'course_id' => $faker->words, 'sequential_lessons' => $faker->words, 'active' => $faker->words
    ];
});
