<?php

use Illuminate\Database\Seeder;
use App\Models\Telemetry;
use App\Models\DeviceType;

class TelemetrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $telemetries = Telemetry::all();
        if (!empty($telemetries)) {
            foreach ($telemetries as $telemetry) {
                $telemetry->delete();
            }
        }

        Telemetry::insert(array(
                array('telemetry_id' => 1, 'name' =>'energy'),
                array('telemetry_id' => 2, 'name' =>'temperature'),
                array('telemetry_id' => 3, 'name' =>'event'),
                array('telemetry_id' => 4, 'name' =>'emergency'),
            )
        );


        // device: light, commands: on|off|lock|unlock|fullLock|dim
        $device = DeviceType::find(1);
        $device->telemetry()->attach([1]);

        // device: em light, commands:
        $device = DeviceType::find(2);
        $device->telemetry()->attach([1]);

        // device: window sensor, commands:
        $device = DeviceType::find(3);

        // device: electric switch heater, commands: on|off|set
        $device = DeviceType::find(4);
        $device->telemetry()->attach([2]);

        // device: water flow, commands:
        $device = DeviceType::find(5);
        $device->telemetry()->attach([1]);

        // device: energy monitor, commands:
        $device = DeviceType::find(6);
        $device->telemetry()->attach([1]);

        // device: socket, commands: on|off|lock|unlock|fullLock
        $device = DeviceType::find(7);

        // device: hot water heater, commands: on|off
        $device = DeviceType::find(8);
        $device->telemetry()->attach([1]);

        // device: PIR, commands:
        $device = DeviceType::find(9);
    }
}
