<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\LabelLanguage;
use Faker\Generator as Faker;

$factory->define(LabelLanguage::class, function (Faker $faker) {
    return [
        // 'label_id' => $faker->words, 'language_id' => $faker->words, 'translation' => $faker->words
    ];
});
