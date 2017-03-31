<?php
/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 15/03/2017
 * Time: 16:59
 */

namespace App\Services\IoT;


use App\Models\Device;
use Iterator;

interface IotDataQueryable
{
    /**
     * @param Device $device
     * @param $type
     * @param array ...$configuration
     * @return mixed
     */
    public function getDeviceData(Device $device, $type, ...$configuration);

    /**
     * @param $from
     * @return mixed
     */
    public function setFrom($from);

    /**
     * @param $to
     * @return mixed
     */
    public function setTo($to);

    /**
     * @param $resultsPerPage
     * @return mixed
     */
    public function setResultsPerPage($resultsPerPage);

    /**
     * @param $pageNumber
     * @return mixed
     */
    public function setPageNumber($pageNumber);
}