<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Lightwave::class, function (Faker\Generator $faker) {
    return [
        'code' => $faker->uuid,
        'vendor_id' => $faker->numberBetween(10000, 99999),
    ];
});
