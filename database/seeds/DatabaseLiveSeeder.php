<?php

use Illuminate\Database\Seeder;

class DatabaseLiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DeviceTypeSeeder::class);
        $this->call(ActivityTypeSeeder::class);
    }
}
