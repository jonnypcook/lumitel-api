<?php

namespace App\Providers;

use App\Services\IoT\IotDiscoverable;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class DiscoverServiceProvider extends ServiceProvider
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
        $this->app->when(\App\Console\Commands\Lightwave\Consume::class)
            ->needs(IotDiscoverable::class)
            ->give(function ($app) {
                return $app->make('Lightwave\DiscoverService');
            });

        $this->app->when(\App\Console\Commands\LiteIP\Consume::class)
            ->needs(IotDiscoverable::class)
            ->give(function ($app) {
                return $app->make('LiteIP\DiscoverService');
            });

        // Discovery services
        $this->app->bind('Lightwave\DiscoverService', function ($app) {
            return new \App\Services\IoT\Lightwave\Discover(
                Config::get('iot.litewaverf.credentials.clientId'),
                Config::get('iot.litewaverf.api.discovery'));
        });

        $this->app->bind('LiteIP\DiscoverService', function ($app) {
            return new \App\Services\IoT\LiteIP\Discover(
                Config::get('iot.liteip.api.project'),
                Config::get('iot.liteip.api.drawing'),
                Config::get('iot.liteip.api.device'));
        });

        // Authentication services
        $this->app->bind('Lightwave\AuthenticateService', function ($app) {
            return new \App\Services\IoT\Lightwave\Authenticate(
                Config::get('iot.litewaverf.credentials.clientId'),
                Config::get('iot.litewaverf.credentials.secret'),
                Config::get('iot.litewaverf.api.auth'));
        });

    }
}
