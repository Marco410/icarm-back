<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Label;
use Faker\Generator as Faker;

$factory->define(Label::class, function (Faker $faker) {
    return [
        // 'name' => $faker->words, 'base' => $faker->words, 'active' => $faker->words
    ];
});
