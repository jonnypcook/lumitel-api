<?php

namespace App\Services\IoT\Lightwave\Command;

/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 01/03/2017
 * Time: 17:13
 */
class Trv extends \App\Services\IoT\Lightwave\Command implements Settable
{

    /**
     * @param $value
     * @return mixed
     */
    public function set($value)
    {
        return $this->sendCommand('trv', 'set', $value);
    }
}