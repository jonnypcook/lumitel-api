<?php

namespace App\Services\IoT\Lightwave\Command;

/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 01/03/2017
 * Time: 17:13
 */
class ElectricSwitch extends \App\Services\IoT\Lightwave\Command implements Switchable
{

    public function switchOn()
    {
        return $this->sendCommand('electricSwitch', 'switch_on');
    }

    public function switchOff()
    {
        return $this->sendCommand('electricSwitch', 'switch_off');
    }
}