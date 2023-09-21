<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\CourseUser;
use Faker\Generator as Faker;

$factory->define(CourseUser::class, function (Faker $faker) {
    return [
        // 'user_id' => $faker->words, 'course_id' => $faker->words
    ];
});
