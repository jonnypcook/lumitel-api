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
$factory->define(App\Device::class, function (Faker\Generator $faker) {
    $deviceType = $faker->numberBetween(1, 6);
    $provider = ($deviceType < 3) ?
        function () {
            return factory(App\Liteip::class)->create()->liteip_id;
        } :
        function () {
            return factory(App\Lightwave::class)->create()->lightwave_id;
        };

    return [
        'device_type_id' => $deviceType,
        'emergency' => ($deviceType === 2),
        'serial' => $faker->numberBetween(8300000, 8399999),
        'provider_id' => $provider,
        'provider_type' => ($deviceType < 3) ? 'App\\Liteip' : 'App\\Lightwave'
    ];
});
