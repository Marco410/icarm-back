<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\ClientUser;
use Faker\Generator as Faker;

$factory->define(ClientUser::class, function (Faker $faker) {
    return [
        // 'user_id' => $faker->words, 'client_id' => $faker->words, 'active' => $faker->words
    ];
});
