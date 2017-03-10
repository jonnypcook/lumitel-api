<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiValidation;
use App\Repositories\DeviceRepository;
use App\Repositories\InstallationRepository;
use App\Repositories\SpaceRepository;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthoriseInstallation
{
    use ApiValidation;

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws AuthorizationException
     * @throws ModelNotFoundException
     * @throws BadRequestHttpException
     */
    public function handle(Request $request, Closure $next)
    {
        // check parameters
        $this->runValidator([
                'installationId' => 'bail|numeric|min:1',
                'spaceId' => 'bail|numeric|min:1',
                'deviceId' => 'bail|numeric|min:1',
            ], array_merge($request->all(), $request->route()->parameters()));

        if ($this->checkInstallation($request->installationId, $request)) {
            return $next($request);
        }

        if ($this->checkSpace($request->spaceId, $request)) {
            return $next($request);
        }

        if ($this->checkDevice($request->deviceId, $request)) {
            return $next($request);
        }

        throw new BadRequestHttpException('missing request parameters');
    }

    /**
     * @param $installationId
     * @return bool
     * @throws ModelNotFoundException
     * @throws AuthorizationException
     */
    protected function checkInstallation($installationId, Request $request) {
        if (!$installationId) {
            return false;
        }

        $installationRepository = new InstallationRepository();
        $installation = $installationRepository
            ->with(['users'])
            ->find($installationId);

        if (!$installation) {
            throw new ModelNotFoundException();
        }

        //If access not granted
        if ($installation->hasUser(\Auth::user()) !== true) {
            throw new AuthorizationException('Permission to installation resource not granted');
        }

        $request->merge(['installation' => $installation]);

        return true;
    }


    /**
     * @param $spaceId
     * @return bool
     * @throws AuthorizationException
     */
    protected function checkSpace($spaceId, Request $request) {
        if (!$spaceId) {
            return false;
        }

        $spaceRepository = new SpaceRepository();
        $space = $spaceRepository
            ->with(['installation'])
            ->find($spaceId);

        if (!$space) {
            throw new ModelNotFoundException();
        }

        //If access not granted
        if ($space->installation->hasUser(\Auth::user()) !== true) {
            throw new AuthorizationException('Permission to space resource not granted');
        }

        $request->merge([
            'installation' => $space->installation,
            'space' => $space
        ]);

        return true;
    }

    /**
     * @param $deviceId
     * @return bool
     * @throws AuthorizationException
     * @throws ModelNotFoundException
     */
    protected function checkDevice($deviceId, Request $request) {
        if (!$deviceId) {
            return false;
        }

        $deviceRepository = new DeviceRepository();
        $device = $deviceRepository
            ->with(['space'])
            ->find($deviceId);

        if (!$device) {
            throw new ModelNotFoundException();
        }

        //If access not granted
        if ($device->space->installation->hasUser(\Auth::user()) !== true) {
            throw new AuthorizationException('Permission to device resource not granted');
        }

        $request->route()->forgetParameter('deviceId');
        $request->route()->setParameter('device', $device);

        $request->merge([
            'installation' => $device->space->installation,
            'space' => $device->space,
            'device' => $device
        ]);


        return true;
    }
}
