<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeviceType;
use App\Device;
use App\Installation;
use App\Space;
use App\Lightwave;
use App\Liteip;

class QuestionsController extends Controller
{
    //
    public function repopulate () {
        $devices = Device::all();
        if (count($devices)) {
            return;
        }

        // create installations
        $installation1 = new Installation();
        $installation1->name = 'Test Installation 1';
        $installation1->commissioned = new \DateTime();
        $installation1->save();

        $installation2 = new Installation();
        $installation2->name = 'Test Installation 2';
        $installation2->commissioned = new \DateTime();
        $installation2->save();

        // create spaces
        $space1 = new Space();
        $space1->name = 'Test Space 1';
        $space1->description = 'Test space 1 floor 1';
        $space1->floor = 1;
        $space1->installation_id = $installation1->installation_id;
        $space1->save();

        $space2 = new Space();
        $space2->name = 'Test Space 2';
        $space2->description = 'Test space 2 floor 2';
        $space2->floor = 2;
        $space2->installation_id = $installation1->installation_id;
        $space2->save();

        $space3 = new Space();
        $space3->name = 'Test Space 3';
        $space3->description = 'Test space 3 floor 0';
        $space3->floor = 0;
        $space3->installation_id = $installation2->installation_id;
        $space3->save();

        for ($i = 0; $i < 5; $i++) {
            if (($i % 2) === 0) {
                $provider = new Lightwave();
                $provider->code = 'CODE#' . $i;
                $provider->vendor_id = $i;
            } else {
                $provider = new Liteip();
                $provider->serial = 'SERIAL#' . $i;
                $provider->vendor_id = $i;
            }
            $provider->save();


            $device = new Device;
            $device->emergency = ($i === 3);
            $device->serial = $i;
            $device->device_type_id = 1;
            $device->space_id = $space1->space_id;
            $device->provider_id = (($i % 2) === 0) ? $provider->lightwave_id : $provider->liteip_id;
            $device->provider_type = (($i % 2) === 0) ? 'App\Lightwave' : 'App\Liteip';
            $device->save();
        }
//        for ($i = 5; $i < 7; $i++) {
//            $device = new Device;
//            $device->emergency = true;
//            $device->serial = $i;
//            $device->device_type_id = 1;
//            $device->space_id = $space2->space_id;
//            $device->save();
//        }
//        for ($i = 7; $i < 20; $i++) {
//            $device = new Device;
//            $device->emergency = (($i % 3) === 0);
//            $device->serial = $i;
//            $device->device_type_id = 1;
//            $device->space_id = $space3->space_id;
//            $device->save();
//        }






    }

    /**
     * Display a listing of the resource
     *
     * @return Response
     */
    public function index()
    {
        //$this->repopulate();

        $devices = DeviceType::find(1)->devices;
        foreach ($devices as $device) {
            echo $device, '<br>';
            echo $device->provider, '<br>';
        }
        echo '<hr>';

        $lightwaves = Lightwave::all();
        foreach ($lightwaves as $lightwave) {
            echo $lightwave, '<br>';
            echo $lightwave->device, '<br>';
        }
        echo '<hr>';

        $lightips = Liteip::all();
        foreach ($lightips as $lightip) {
            echo $lightip, '<br>';
            echo $lightip->device, '<br>';
        }
        echo '<hr>';

//        $devices = Device::all();
//        foreach ($devices as $device) {
//            echo $device->deviceType->name, '<br>';
//        }
//        echo '<hr>';

//        $devices = Space::find(3)->devices;
//        foreach ($devices as $device) {
//            echo $device, '<br>';
//        }
//        echo '<hr>';

//        $devices = Device::all();
//        foreach ($devices as $device) {
//            echo $device->space->name, '<br>';
//        }
//        echo '<hr>';

//        $spaces = Installation::find(1)->spaces;
//        foreach ($spaces as $space) {
//            echo $space, '<br>';
//        }
//        echo '<hr>';

//        $spaces = Space::all();
//        foreach ($spaces as $space) {
//            echo $space->installation->name, '<br>';
//        }
//        echo '<hr>';

        die('STOP');

        return array(
            1 => "John",
            2 => "Mary",
            3 => "Steven"
        );
    }
}
