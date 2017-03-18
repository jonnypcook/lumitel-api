<?php

namespace App\Services\IoT\Lightwave\Command;

/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 01/03/2017
 * Time: 17:13
 */
class Relay extends \App\Services\IoT\Lightwave\Command implements Openable, Lockable
{

    public function lock()
    {
        return $this->sendCommand('relay', 'lock');
    }

    public function unlock()
    {
        return $this->sendCommand('relay', 'unlock');
    }

    public function fullyLock()
    {
        return $this->sendCommand('relay', 'fullyLock');
    }

    public function open()
    {
        return $this->sendCommand('relay', 'open');
    }

    public function close()
    {
        return $this->sendCommand('relay', 'close');
    }
}