<?php

use Illuminate\Database\Seeder;
use App\Models\DeviceType;

class DeviceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deviceTypes = DeviceType::all();
        if (!empty($deviceTypes)) {
            foreach ($deviceTypes as $deviceType) {
                $deviceType->delete();
            }
        }

        DeviceType::insert(array(
                array('device_type_id' => 1, 'name' =>'led'),
                array('device_type_id' => 2, 'name' =>'emergency led'),
                array('device_type_id' => 3, 'name' =>'window sensor'),
                array('device_type_id' => 4, 'name' =>'electric switch heater'),
                array('device_type_id' => 5, 'name' =>'water flow'),
                array('device_type_id' => 6, 'name' =>'energy monitor'),
                array('device_type_id' => 7, 'name' =>'socket switch'),
                array('device_type_id' => 8, 'name' =>'hot water heater'),
                array('device_type_id' => 600, 'name' => 'unknown')
            )
        );
    }
}
