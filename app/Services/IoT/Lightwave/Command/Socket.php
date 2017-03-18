<?php

namespace App\Services\IoT\Lightwave\Command;

/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 01/03/2017
 * Time: 17:13
 */
class Socket extends \App\Services\IoT\Lightwave\Command implements Switchable, Lockable
{

    public function lock()
    {
        return $this->sendCommand('socket', 'lock');
    }

    public function unlock()
    {
        return $this->sendCommand('socket', 'unlock');
    }

    public function fullyLock()
    {
        return $this->sendCommand('socket', 'fullyLock');
    }

    public function switchOn()
    {
        return $this->sendCommand('socket', 'switch_on');
    }

    public function switchOff()
    {
        return $this->sendCommand('socket', 'switch_off');
    }
}