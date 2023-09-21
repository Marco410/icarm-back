<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Section;
use Faker\Generator as Faker;

$factory->define(Section::class, function (Faker $faker) {
    return [
        // 'course_id' => $faker->words, 'name' => $faker->words, 'description' => $faker->words, 'active' => $faker->words
    ];
});
