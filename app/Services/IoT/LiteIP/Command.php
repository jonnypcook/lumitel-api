<?php
namespace App\Services\IoT\LiteIP;

use App\Models\Device;
use App\Services\IoT\IotCommandable;
use Eloquent;
use DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Command implements IotCommandable
{

    public function setDevice(Device $device)
    {
        // TODO: Implement setDevice() method.
    }

    public function getDevice()
    {
        // TODO: Implement getDevice() method.
    }
}