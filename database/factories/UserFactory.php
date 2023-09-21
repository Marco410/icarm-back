<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Person;
use App\Models\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {

    $email = $faker->email;
    
    return [
        'person_id' => factory(Person::class),
        'profile_id' => 1,
        'username' => $email,
        'password' => hash('sha512', 'password'),
        'email' => $email,
        'active' => 1
    ];
});
