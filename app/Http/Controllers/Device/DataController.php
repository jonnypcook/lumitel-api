<?php

namespace App\Http\Controllers\Device;

use App\Services\IoT\Lightwave\Data;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id, $type, $dateFrom, $dateTo)
    {
        $dtFrom = \DateTime::createFromFormat('Y-m-d\TH:i:s', $dateFrom);
        $dtTo = \DateTime::createFromFormat('Y-m-d\TH:i:s', $dateTo);

        $dataService = app()->make('Lightwave\DataService');
        $dataService->setFrom($dtFrom);
        $dataService->setTo($dtTo);
        $dataService->getData($id, $type);
        $dataService->setResultsPerPage(350);

//        $dataService->setFrom
        echo 'here';
        die();
//        $spaceId = $request->get('spaceId', false);
//        $installationId = $request->get('installationId', false);
//
//        if ($spaceId !== false) {
//            $devices = $deviceRepository
//                ->with(['provider', 'deviceType'])
//                ->where('space_id', '=', $spaceId)
//                ->findAll();
//        } else {
//            $devices = Device::whereHas('space', function($query) use($installationId) {
//                $query->where('installation_id', $installationId);
//            })->get();
//
//
//        }
//
//
//
//        return $this->response->withCollection($devices, new DeviceTransformer());
    }
}
