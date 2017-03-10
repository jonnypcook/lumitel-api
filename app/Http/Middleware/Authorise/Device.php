<?php
/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 10/03/2017
 * Time: 14:54
 */

namespace App\Http\Middleware\Authorise;


use App\Repositories\DeviceRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait Device
{
    /**
     * @param $deviceId
     * @return \App\Models\Device
     * @throws AuthorizationException
     * @throws ModelNotFoundException
     */
    protected function authoriseDevice($deviceId) {
        if (!$deviceId) {
            throw new ModelNotFoundException();
        }

        if (!preg_match('/^[\d]+$/', $deviceId)) {
            throw new BadRequestHttpException('deviceId should be an integer.');
        }

        $deviceRepository = new DeviceRepository();
        $device = $deviceRepository->with(['space'])->find($deviceId);

        if (!$device) {
            throw new ModelNotFoundException();
        }

        //If access not granted
        if ($device->space->installation->hasUser(\Auth::user()) !== true) {
            throw new AuthorizationException('Permission to device resource not granted');
        }

        return $device;
    }
}