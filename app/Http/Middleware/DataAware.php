<?php

namespace App\Http\Middleware;

use App\Models\Device;
use App\Repositories\TelemetryRepository;
use Closure;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DataAware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $device = $request->device;
        $type = $request->type;

        if (!($device instanceof Device)) {
            throw new BadRequestHttpException('A valid device could not be found');
        }

        $telemetryRepository = new TelemetryRepository();
        $telemetryItem = $telemetryRepository->where('name', '=', $type)->findFirst();

        if (!$telemetryItem) {
            throw new BadRequestHttpException(sprintf('Telemetry type %s could not be found in collection', $type));
        }


        foreach ($device->deviceType->telemetry as $telemetry) {
            if ($telemetry->telemetry_id == $telemetryItem->telemetry_id) {
                return $next($request);
            }
        }

        throw new BadRequestHttpException(sprintf('Device type %s is incompatible with %s data', $device->deviceType->name, $type));
    }
}
