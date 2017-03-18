<?php

namespace App\Services\IoT\Lightwave\Command;

/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 01/03/2017
 * Time: 17:13
 */
class Event extends \App\Services\IoT\Lightwave\Command implements Startable
{

    public function start()
    {
        return $this->sendCommand('event', 'start');
    }

    public function stop()
    {
        return $this->sendCommand('event', 'stop');
    }
}