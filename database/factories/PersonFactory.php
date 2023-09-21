<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Person;
use Faker\Generator as Faker;

$factory->define(Person::class, function (Faker $faker) {

    $gender = $faker->randomElement([Person::FEMALE, Person::MALE]);

    return [
        'name' => ($gender == Person::FEMALE) ? $faker->firstNameFemale : $faker->firstNameMale,
        'lastname' => $faker->lastName,
        'second_surname' => $faker->lastName,
        'gender' => $gender,
        'birthdate' => $faker->date(),
        'avatar' => null,
        'active' => 1
    ];
});
