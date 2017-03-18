<?php

namespace App\Services\IoT\Lightwave\Command;

/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 01/03/2017
 * Time: 17:13
 */
class Light extends \App\Services\IoT\Lightwave\Command implements Switchable, Lockable, Dimmable
{

    public function dim($value)
    {
        return $this->sendCommand('light', 'dim', $value);
    }

    public function lock()
    {
        return $this->sendCommand('light', 'lock');
    }

    public function unlock()
    {
        return $this->sendCommand('light', 'unlock');
    }

    public function fullyLock()
    {
        return $this->sendCommand('light', 'fullylock');
    }

    public function switchOn()
    {
        return $this->sendCommand('light', 'switch_on');
    }

    public function switchOff()
    {
        return $this->sendCommand('light', 'switch_off');
    }
}