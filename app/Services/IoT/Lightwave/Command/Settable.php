<?php
/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 01/03/2017
 * Time: 17:42
 */

namespace App\Services\IoT\Lightwave\Command;


interface Settable
{
    public function set($value);
}