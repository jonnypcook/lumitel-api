<?php
/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 27/02/2017
 * Time: 11:54
 */

namespace App\Services\IoT;


interface IotTokenable
{
    public function getToken();
    public function requestToken();
    public function renewToken();
}