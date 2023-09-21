<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\UserContext;
use Faker\Generator as Faker;

$factory->define(UserContext::class, function (Faker $faker) {
    return [
        // 'user_id' => $faker->words, 'client_id' => $faker->words
    ];
});
