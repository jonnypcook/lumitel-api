<?php
/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 01/03/2017
 * Time: 17:43
 */

namespace App\Services\IoT\Lightwave\Command;


interface Dimmable
{
    public function dim($value);
}