<?php
/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 24/02/2017
 * Time: 14:19
 */

namespace App\Services\IoT;


use App\Models\Installation;
use App\Models\Owner;

interface IotDiscoverable
{
    /**
     * @param Owner $owner
     * @param Installation $installation
     * @param mixed $vendorIdentifier
     * @return mixed
     */
    public function discover(Owner $owner, Installation $installation, $vendorIdentifier);
}