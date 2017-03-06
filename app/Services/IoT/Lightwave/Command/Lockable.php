<?php
/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 01/03/2017
 * Time: 17:36
 */

namespace App\Services\IoT\Lightwave\Command;


interface Lockable
{
    public function lock();
    public function unlock();
    public function fullyLock();
}