<?php

use Illuminate\Database\Seeder;
use App\Models\DeviceLiteIpStatus;

class DeviceLiteIpStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deviceLiteipStatuses = DeviceLiteIpStatus::all();
        if (!empty($deviceLiteipStatuses)) {
            foreach ($deviceLiteipStatuses as $deviceLiteipStatus) {
                $deviceLiteipStatus->delete();
            }
        }

        DeviceLiteIpStatus::insert(array(
                array('device_liteip_status_id' => 1, 'name' =>'No Fault', 'description' =>'No Fault', 'fault' => false),
                array('device_liteip_status_id' => 2, 'name' =>'No Fault', 'description' =>'In Test – 30s Function', 'fault' => false),
                array('device_liteip_status_id' => 3, 'name' =>'No Fault', 'description' =>'In Test – 3Hr Duration', 'fault' => false),
                array('device_liteip_status_id' => 4, 'name' =>'Lamp Fault', 'description' =>'Lamp Fault', 'fault' => true),
                array('device_liteip_status_id' => 5, 'name' =>'Charge Fault', 'description' =>'Charge Fault', 'fault' => true),
                array('device_liteip_status_id' => 6, 'name' =>'Battery Fault', 'description' =>'Battery Fault', 'fault' => true),
                array('device_liteip_status_id' => 7, 'name' =>'Fault (Unspecified)', 'description' =>'Fault (Unspecified)', 'fault' => true),
                array('device_liteip_status_id' => 600, 'name' =>'Status Unknown', 'description' =>'Status Unknown', 'fault' => true),
            )
        );
    }
}
