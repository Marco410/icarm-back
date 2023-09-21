<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\LessonSource;
use Faker\Generator as Faker;

$factory->define(LessonSource::class, function (Faker $faker) {
    return [
        // 'lesson_id' => $faker->words, 'src' => $faker->words, 'type' => $faker->words, 'title' => $faker->words, 'description' => $faker->words, 'content' => $faker->words, 'active' => $faker->words
    ];
});
