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
$factory->define(App\Models\Space::class, function (Faker\Generator $faker) {
    return [
        'parent_id' => null,
        'image_id' => null,
        'name' => implode(' ', $faker->words($faker->numberBetween(1,5))),
        'level' => 0,
        'width' => 850,
        'height' => 550,
        'left' => 0,
        'top' => 0,
        'installation_id' => function () {
            return factory(App\Models\Installation::class)->create()->installation_id;
        },
    ];
});
