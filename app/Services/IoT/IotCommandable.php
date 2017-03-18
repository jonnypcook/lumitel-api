<?php
/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 17/03/2017
 * Time: 21:51
 */

namespace App\Services\IoT;


use App\Models\Device;

interface IotCommandable
{
    public function setDevice(Device $device);
    public function getDevice();
}