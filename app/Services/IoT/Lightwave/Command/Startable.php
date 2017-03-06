<?php
/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 01/03/2017
 * Time: 17:37
 */

namespace App\Services\IoT\Lightwave\Command;


interface Startable
{
    public function start();
    public function stop();
}