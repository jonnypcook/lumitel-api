<?php

namespace App\Providers;

use App\Models\DeviceLightWave;
use App\Services\IoT\IotCommandable;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Conditional discovery rules
        $this->app
            ->when('\App\Http\Controllers\Device\CommandController')
            ->needs(IotCommandable::class)
            ->give(function ($app) {
                switch ($app->request->device->provider_type) {
                    case DeviceLightWave::class:
                        $deviceId = $app->request->device->device_type_id;

                        if ($deviceId === 1) {
                            return $app->make('Lightwave\Command\Light');
                        } elseif ($deviceId === 2) {
                            return $app->make('Lightwave\Command\Light');
                        } elseif ($deviceId === 4) {
                            return $app->make('Lightwave\Command\Temperature');
                        } elseif ($deviceId === 7) {
                            return $app->make('Lightwave\Command\Socket');
                        } elseif ($deviceId === 8) {
                            return $app->make('Lightwave\Command\ElectricSwitch');
                        }
                        throw new BadRequestHttpException(sprintf('Unexpected device type "%s" in command service provider', $deviceId));
                        break;
                    case DeviceLiteIp::class:
                        return $app->make('LiteIP\CommandService');
                }

                throw new BadRequestHttpException(sprintf('Unexpected provider type "%s" in command service provider', $app->request->device->provider_type));
            });

        // LiteIP Command services
        $this->app->bind('LiteIP\CommandService', function ($app) {
            return new \App\Services\IoT\LiteIP\Command();
        });


        // Lightwave Command services
        $this->app->bind('Lightwave\Command\ElectricSwitch', function ($app) {
            return \App\Services\IoT\Lightwave\Command::factory('ElectricSwitch', $app->request->device);
        });

        $this->app->bind('Lightwave\Command\Event', function ($app) {
            return \App\Services\IoT\Lightwave\Command::factory('Event', $app->request->device);
        });

        $this->app->bind('Lightwave\Command\Light', function ($app) {
            return \App\Services\IoT\Lightwave\Command::factory('Light', $app->request->device);
        });

        $this->app->bind('Lightwave\Command\Mood', function ($app) {
            return \App\Services\IoT\Lightwave\Command::factory('Mood', $app->request->device);
        });

        $this->app->bind('Lightwave\Command\Relay', function ($app) {
            return \App\Services\IoT\Lightwave\Command::factory('Relay', $app->request->device);
        });

        $this->app->bind('Lightwave\Command\Socket', function ($app) {
            return \App\Services\IoT\Lightwave\Command::factory('Socket', $app->request->device);
        });

        $this->app->bind('Lightwave\Command\Temperature', function ($app) {
            return \App\Services\IoT\Lightwave\Command::factory('Temperature', $app->request->device);
        });

        $this->app->bind('Lightwave\Command\Trv', function ($app) {
            return \App\Services\IoT\Lightwave\Command::factory('Trv', $app->request->device);
        });
    }


}
