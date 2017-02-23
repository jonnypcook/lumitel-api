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
$factory->define(App\Models\Floor::class, function (Faker\Generator $faker) {
    $level = $faker->numberBetween(0, 20);
    return [
        'name' => 'Level ' . $level,
        'level' => $level,
        'image' => false,
        'installation_id' => function () {
            return factory(App\Models\Installation::class)->create()->installation_id;
        }
    ];
});
