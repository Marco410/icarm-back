<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\AccessLog;
use Faker\Generator as Faker;

$factory->define(AccessLog::class, function (Faker $faker) {
    return [
        // 'user_id' => $faker->words
    ];
});
