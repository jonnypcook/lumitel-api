<?php

use Illuminate\Database\Seeder;

class SpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $spaces = factory(App\Models\Space::class, 3)
            ->create()
            ->each(function ($space) {
                for ($i = 0; $i < 5; $i++) {
                    $space->devices()->save(factory(App\Models\Device::class)->make());
                }
            });
    }
}
