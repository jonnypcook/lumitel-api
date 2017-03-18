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
        $this->call(RolePermissionSeeder::class);
        $this->call(ImageTypeSeeder::class);
        $this->call(DeviceLiteIpStatusSeeder::class);
        $this->call(CommandSeeder::class);
        $this->call(TelemetrySeeder::class);
    }
}
