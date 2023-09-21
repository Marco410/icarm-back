<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Lesson;
use Faker\Generator as Faker;

$factory->define(Lesson::class, function (Faker $faker) {
    return [
        // 'course_id' => $faker->words, 'lesson_type_id' => $faker->words, 'name' => $faker->words, 'description' => $faker->words, 'link' => $faker->words, 'duration' => $faker->words, 'active' => $faker->words
    ];
});
