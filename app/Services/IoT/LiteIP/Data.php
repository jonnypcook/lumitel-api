<?php
namespace App\Services\IoT\LiteIP;

use App\Models\Device;
use App\Services\IoT\IotDataQueryable;
use Eloquent;
use DB;

class Data implements IotDataQueryable
{

    /**
     * @param Device $device
     * @param $type
     * @param array ...$configuration
     * @return mixed
     */
    public function getDeviceData(Device $device, $type, ...$configuration)
    {
        die('liteip data');
        // TODO: Implement getDeviceData() method.
    }

    /**
     * @param $from
     * @return mixed
     */
    public function setFrom($from)
    {
        // TODO: Implement setFrom() method.
    }

    /**
     * @param $to
     * @return mixed
     */
    public function setTo($to)
    {
        // TODO: Implement setTo() method.
    }

    /**
     * @param $resultsPerPage
     * @return mixed
     */
    public function setResultsPerPage($resultsPerPage)
    {
        // TODO: Implement setResultsPerPage() method.
    }

    /**
     * @param $pageNumber
     * @return mixed
     */
    public function setPageNumber($pageNumber)
    {
        // TODO: Implement setPageNumber() method.
    }
}