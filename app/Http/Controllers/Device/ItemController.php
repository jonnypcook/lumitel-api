<?php

namespace App\Http\Controllers\Device;

use App\Http\Requests\DeviceList;
use App\Models\Device;
use App\Repositories\DeviceRepository;
use App\Transformer\DeviceTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        if ($spaceId !== false) {
            $devices = $deviceRepository
                ->with(['provider', 'deviceType'])
                ->where('space_id', '=', $spaceId)
                ->findAll();
        } else {
            $devices = $deviceRepository
                ->findWhereHas([
                    'space',
                    function($query) use($installationId) {
                        $query->where('installation_id', $installationId);
                    }
                ]);
        }

        return $this->response->withCollection($devices, new DeviceTransformer());
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
