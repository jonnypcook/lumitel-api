<?php

namespace App\Services\IoT\Lightwave\Command;

/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 01/03/2017
 * Time: 17:13
 */
class Temperature extends \App\Services\IoT\Lightwave\Command implements Switchable, Settable
{

    public function set($value)
    {
        return $this->sendCommand('temperature', 'set', $value);
    }

    public function switchOn()
    {
        return $this->sendCommand('temperature', 'switch_on');
    }

    public function switchOff()
    {
        return $this->sendCommand('temperature', 'switch_off');
    }
}