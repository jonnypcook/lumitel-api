<?php

use Illuminate\Database\Seeder;
use App\Models\Command;
use App\Models\DeviceType;

class CommandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $commands = Command::all();
        if (!empty($commands)) {
            foreach ($commands as $command) {
                $command->delete();
            }
        }

        Command::insert(array(
                array('command_id' => 1, 'name' =>'on'),
                array('command_id' => 2, 'name' =>'off'),
                array('command_id' => 3, 'name' =>'lock'),
                array('command_id' => 4, 'name' =>'unlock'),
                array('command_id' => 5, 'name' =>'fullLock'),
                array('command_id' => 6, 'name' =>'dim'),
                array('command_id' => 7, 'name' =>'open'),
                array('command_id' => 8, 'name' =>'close'),
                array('command_id' => 9, 'name' =>'start'),
                array('command_id' => 10, 'name' =>'stop'),
                array('command_id' => 11, 'name' =>'set'),
            )
        );


        // device: light, commands: on|off|lock|unlock|fullLock|dim
        $device = DeviceType::find(1);
        $device->commands()->attach([1, 2, 3, 4, 5, 6]);

        // device: em light, commands:
        $device = DeviceType::find(2);

        // device: window sensor, commands:
        $device = DeviceType::find(3);

        // device: electric switch heater, commands: on|off|set
        $device = DeviceType::find(4);
        $device->commands()->attach([1, 2, 11]);

        // device: water flow, commands:
        $device = DeviceType::find(5);

        // device: energy monitor, commands:
        $device = DeviceType::find(6);

        // device: socket, commands: on|off|lock|unlock|fullLock
        $device = DeviceType::find(7);
        $device->commands()->attach([1, 2, 3, 4, 5]);

        // device: hot water heater, commands: on|off
        $device = DeviceType::find(8);
        $device->commands()->attach([1, 2]); // data for em led

        // device: PIR, commands:
        $device = DeviceType::find(9);


    }
}
