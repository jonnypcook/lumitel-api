<?php

use Illuminate\Database\Seeder;

class DatabaseTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // setup base seeders
        $this->call(DatabaseLiveSeeder::class);

        // call test data generation seeders
        $user = factory(App\Models\User::class)->create(['name' => 'Jonny Cook', 'email' => 'jonny.p.cook@gmail.com']);
        $user->roles()->attach([1]);

//        $spaces = factory(App\Models\Space::class, 3)
//            ->create()
//            ->each(function ($space) {
//                for ($i = 0; $i < 5; $i++) {
//                    $space->devices()->save(factory(App\Models\Device::class)->make());
//                }
//            });

    }
}
