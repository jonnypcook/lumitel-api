<?php

namespace App\Http\Middleware;

use App\Models\Device;
use App\Repositories\CommandRepository;
use Closure;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CommandAware
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

        $commandRepository = new  CommandRepository();
        $commandItem = $commandRepository->where('name', '=', $type)->findFirst();

        if (!$commandItem) {
            throw new BadRequestHttpException(sprintf('Command type %s could not be found in collection', $type));
        }


        foreach ($device->deviceType->commands as $command) {
            if ($command->command_id == $commandItem->command_id) {
                return $next($request);
            }
        }

        throw new BadRequestHttpException(sprintf('Device type %s is incompatible with %s command', $device->deviceType->name, $type));

    }
}
