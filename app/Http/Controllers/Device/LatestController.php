<?php

namespace App\Http\Controllers\Device;

use App\Http\Middleware\Authorise\Device;
use App\Repositories\DeviceRepository;
use App\Services\IoT\IotDataQueryable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LatestController extends Controller
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
     * @param \App\Models\Device $device
     * @param $type
     * @return mixed
     */
    protected function getApiData(\App\Models\Device $device, $type) {
        $dtFrom = new \DateTime();
        $dtFrom->setTimestamp(mktime(0, 0, 0, date('m'), date('d'), date('Y')));

        $dtTo = new \DateTime();
        $dtTo->setTimestamp(mktime(23, 59, 59, date('m'), date('d'), date('Y')));

        $this->dataService->setFrom($dtFrom);
        $this->dataService->setTo($dtTo);
        $this->dataService->setPageNumber(1);
        $this->dataService->setResultsPerPage(1);

        $data = $this->dataService->getDeviceData($device, $type, true);

        return array_shift($data['rows']);
    }

    /**
     * update repository with data changes
     * @param \App\Models\Device $device
     * @param $latestReading
     */
    protected function saveLatestData(\App\Models\Device $device, $latestReading) {
        if (empty($latestReading)) {
            return;
        }
        $dtUpdated = new \DateTime();
        $dtUpdated->setTimestamp(strtotime($latestReading['utc_time']));

        $deviceRepository = new DeviceRepository();
        $deviceRepository->update($device->device_id, [
            'last_reading_at' => $dtUpdated,
            'last_reading_current' => $latestReading['current_use'],
            'last_reading_total' => $latestReading['total_day_use'],
        ]);
    }

    /**
     * @param Request $request
     * @param $type
     * @param $device
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function emergency(Request $request, $type, $device)
    {
        $data = $this->getApiData($device, $type);

        $this->saveLatestData($device, $data);

        return $this->response->withArray($data);
    }

    /**
     * @param Request $request
     * @param $type
     * @param Device $device
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function energy(Request $request, $type, $device)
    {
        $data = $this->getApiData($device, $type);

        $this->saveLatestData($device, $data);

        return $this->response->withArray((array)$data);
    }

    /**
     * @param Request $request
     * @param $type
     * @param Device $device
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function temperature(Request $request, $type, $device)
    {
        $data = $this->getApiData($device, $type);

        $this->saveLatestData($device, $data);

        return $this->response->withArray($data);
    }

    /**
     * @param Request $request
     * @param $type
     * @param Device $device
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function event(Request $request, $type, $device)
    {
        $data = $this->getApiData($device, $type);

        $this->saveLatestData($device, $data);

        return $this->response->withArray($data);
    }
}
