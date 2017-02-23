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
$factory->define(App\Models\DeviceLiteIp::class, function (Faker\Generator $faker) {
    return [
        'serial' => $faker->numberBetween(8300000, 8399999),
        'vendor_id' => $faker->numberBetween(1, 10000),
        'profile_id' => $faker->numberBetween(1, 20),
        'emergency_checked' => new DateTime(),
        'emergency' => $faker->boolean(),
    ];
});
