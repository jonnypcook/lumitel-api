<?php

namespace App\Http\Controllers\Device;

use App\Http\Requests\DeviceData;
use App\Models\Device;
use App\Services\IoT\IotDataQueryable;
use App\Services\IoT\Lightwave\Data;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataController extends Controller
{
    protected $dataService;

    /**

     * DataController constructor.
     */
    public function __construct(\EllipseSynergie\ApiResponse\Laravel\Response $response, IotDataQueryable $dataService)
    {
        parent::__construct($response);
        $this->dataService = $dataService;
    }


    /**
     * @param Request $request
     * @param DeviceData $deviceData
     * @param $type
     * @param $device
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function emergency(Request $request, DeviceData $deviceData, $type, $device)
    {
        $this->dataService->setFrom(\DateTime::createFromFormat('Y-m-d\TH:i:s', $request->dateFrom));
        $this->dataService->setTo(\DateTime::createFromFormat('Y-m-d\TH:i:s', $request->dateTo));
        $this->dataService->setPageNumber($request->get('pageNumber', 1));
        $this->dataService->setResultsPerPage($request->get('resultsPerPage', 500));
        $data = $this->dataService->getDeviceData($device, $type, true);

        return $this->response->withArray($data);
    }

    /**
     * @param Request $request
     * @param DeviceData $deviceData
     * @param $type
     * @param Device $device
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function energy(Request $request, DeviceData $deviceData, $type, $device)
    {
        $this->dataService->setFrom(\DateTime::createFromFormat('Y-m-d\TH:i:s', $request->dateFrom));
        $this->dataService->setTo(\DateTime::createFromFormat('Y-m-d\TH:i:s', $request->dateTo));
        $this->dataService->setPageNumber($request->get('pageNumber', 1));
        $this->dataService->setResultsPerPage($request->get('resultsPerPage', 500));

        $data = $this->dataService->getDeviceData($device, $type, true);


        return $this->response->withArray($data);
    }

    /**
     * @param Request $request
     * @param DeviceData $deviceData
     * @param $type
     * @param Device $device
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function temperature(Request $request, DeviceData $deviceData, $type, $device)
    {
        $this->dataService->setFrom(\DateTime::createFromFormat('Y-m-d\TH:i:s', $request->dateFrom));
        $this->dataService->setTo(\DateTime::createFromFormat('Y-m-d\TH:i:s', $request->dateTo));
        $this->dataService->setPageNumber($request->get('pageNumber', 1));
        $this->dataService->setResultsPerPage($request->get('resultsPerPage', 500));

        $data = $this->dataService->getDeviceData($device, $type, true);

        return $this->response->withArray($data);
    }

    /**
     * @param Request $request
     * @param DeviceData $deviceData
     * @param $type
     * @param Device $device
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function event(Request $request, DeviceData $deviceData, $type, $device)
    {
        $this->dataService->setFrom(\DateTime::createFromFormat('Y-m-d\TH:i:s', $request->dateFrom));
        $this->dataService->setTo(\DateTime::createFromFormat('Y-m-d\TH:i:s', $request->dateTo));
        $this->dataService->setPageNumber($request->get('pageNumber', 1));
        $this->dataService->setResultsPerPage($request->get('resultsPerPage', 500));

        $data = $this->dataService->getDeviceData($device, $type, true);
        return $this->response->withArray($data);
    }
}
