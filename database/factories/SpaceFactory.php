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
$factory->define(App\Space::class, function (Faker\Generator $faker) {
    return [
        'name' => implode(' ', $faker->words($faker->numberBetween(1,5))),
        'floor_id' => function () {
            return factory(App\Floor::class)->create()->floor_id;
        },
    ];
});
