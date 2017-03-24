<?php

namespace App\Http\Controllers\Device;

use App\Http\Requests\DeviceList;
use App\Models\Device;
use App\Models\DeviceType;
use App\Repositories\DeviceRepository;
use App\Transformer\DeviceTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ItemController extends Controller
{
    /**
     * @param Request $request
     * @param DeviceRepository $deviceRepository
     * @return mixed
     */
    public function index(Request $request, DeviceList $deviceList, DeviceRepository $deviceRepository)
    {

        $spaceId = $request->get('spaceId', false);
        $installationId = $request->get('installationId', false);
        $metering = $request->get('metering', false);
        $summary = $request->get('summary', false);

        $deviceRepository->with(['provider', 'deviceType']);

        if ($metering) {
            $deviceRepository->whereIn('device_type_id', [5, 6]);
        }

        if ($spaceId !== false) {
            $deviceRepository->where('space_id', '=', $spaceId);
        } else {
            $deviceRepository
                ->whereHas('space', function($query) use($installationId) {
                        $query->where('installation_id', $installationId);
                });
        }

        $devices = $deviceRepository->findAll();
        return $this->response->withCollection($devices, new DeviceTransformer());
    }

    /**
     * @param Request $request
     * @param DeviceRepository $deviceRepository
     * @return mixed
     */
    public function summary(Request $request, DeviceList $deviceList, DeviceRepository $deviceRepository)
    {

        $spaceId = $request->get('spaceId', false);
        $installationId = $request->get('installationId', false);

        $deviceSummaryQuery = Device::select('device_type.name', 'device_type.device_type_id', DB::raw("count(device.device_type_id) as count"))
            ->join('device_type', 'device.device_type_id', '=', 'device_type.device_type_id')
            ->groupBy(['device_type.name', 'device_type.device_type_id']);

        if ($installationId !== false) {
            $deviceSummaryQuery
                ->join('space', 'device.space_id', '=', 'space.space_id')
                ->where('space.installation_id', '=', $installationId);
        }

        if ($spaceId !== false) {
            $deviceSummaryQuery->where('device.space_id', '=', $spaceId);
        }

        $deviceSummaries = $deviceSummaryQuery->get();

        $summary = [];
        foreach ($deviceSummaries as $deviceSummary) {
            $summary[] = [
                'name' => $deviceSummary->name,
                'device_type_id' => $deviceSummary->device_type_id,
                'count' => $deviceSummary->count,
            ];
        }

        return $this->response->withArray($summary);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->response->errorUnwillingToProcess();
    }

    /**
     * Display the specified resource.
     *
     * @param Device $device
     * @return mixed
     */
    public function show(Device $device)
    {
        // Return a single task
        return $this->response->withItem($device, new  DeviceTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Device $device
     * @return mixed
     */
    public function update(Request $request, Device $device)
    {
        return $this->response->errorUnwillingToProcess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Device $device
     * @return mixed
     */
    public function destroy(Device $device)
    {
        return $this->response->errorUnwillingToProcess();
    }
}
