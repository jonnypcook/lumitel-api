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
        // TODO: Implement dim() method.
    }

    public function lock()
    {
        // TODO: Implement lock() method.
    }

    public function unlock()
    {
        // TODO: Implement unlock() method.
    }

    public function fullyLock()
    {
        // TODO: Implement fullyLock() method.
    }

    public function switchOn()
    {
        // TODO: Implement switchOn() method.
    }

    public function switchOff()
    {
        // TODO: Implement switchOff() method.
    }
}