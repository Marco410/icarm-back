<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\CourseCourseCategory;
use Faker\Generator as Faker;

$factory->define(CourseCourseCategory::class, function (Faker $faker) {
    return [
        // 'course_id' => $faker->words, 'course_category_id' => $faker->words
    ];
});
