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
$factory->define(App\Installation::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->buildingNumber . ' ' . $faker->streetName,
        'commissioned' => $faker->dateTime(),
        'owner_id' => function () {
            return factory(App\Owner::class)->create()->owner_id;
        },
        'address_id' => function () {
            return factory(App\Address::class)->create()->address_id;
        }
    ];
});
