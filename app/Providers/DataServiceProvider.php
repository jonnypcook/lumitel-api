<?php

namespace App\Providers;

use App\Models\DeviceLightWave;
use App\Models\DeviceLiteIp;
use App\Services\IoT\IotDataQueryable;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Device\DataController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DataServiceProvider extends ServiceProvider
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
            ->when('\App\Http\Controllers\Device\DataController')
            ->needs(IotDataQueryable::class)
            ->give(function ($app) {
                switch ($app->request->device->provider_type) {
                    case DeviceLightWave::class:
                        return $app->make('Lightwave\DataService');
                    case DeviceLiteIp::class:
                        return $app->make('LiteIP\DataService');
                }

                throw new BadRequestHttpException('Unexpected provider type in data controller service providor');
            });

        // Data service
        $this->app->bind('Lightwave\DataService', function ($app) {
            return new \App\Services\IoT\Lightwave\Data(
                Config::get('iot.litewaverf.credentials.clientId'),
                Config::get('iot.litewaverf.api.device-data'));
        });

        $this->app->bind('LiteIP\DataService', function ($app) {
            return new \App\Services\IoT\LiteIP\Data(
                Config::get('iot.liteip.api.project'),
                Config::get('iot.liteip.api.drawing'),
                Config::get('iot.liteip.api.device'));
        });

    }
}
