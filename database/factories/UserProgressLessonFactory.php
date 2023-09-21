<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\UserProgressLesson;
use Faker\Generator as Faker;

$factory->define(UserProgressLesson::class, function (Faker $faker) {
    return [
        // 'user_id' => $faker->words, 'lesson_id' => $faker->words, 'check' => $faker->words
    ];
});
