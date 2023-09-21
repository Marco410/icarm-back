<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Client;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
    return [
        // 'user_id' => $faker->words, 'name' => $faker->words, 'logo' => $faker->words, 'active' => $faker->words
    ];
});
