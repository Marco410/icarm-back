<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\UserSession;
use Faker\Generator as Faker;

$factory->define(UserSession::class, function (Faker $faker) {
    return [
        // 'user_id' => $faker->words, 'token' => $faker->words, 'last_request' => $faker->words, 'number_logins' => $faker->words
    ];
});
