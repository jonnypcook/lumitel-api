<?php

namespace App\Providers;

use App\Repositories\ActivityRepository;
use App\Repositories\ActivityRepositoryContract;
use App\Repositories\ActivityTypeRepository;
use App\Repositories\ActivityTypeRepositoryContract;
use App\Repositories\AddressRepository;
use App\Repositories\AddressRepositoryContract;
use App\Repositories\CommandRepository;
use App\Repositories\CommandRepositoryContract;
use App\Repositories\DeviceLightWaveRepository;
use App\Repositories\DeviceLightWaveRepositoryContract;
use App\Repositories\DeviceLiteipRepository;
use App\Repositories\DeviceLiteipRepositoryContract;
use App\Repositories\DeviceLiteIpStatusRepository;
use App\Repositories\DeviceLiteIpStatusRepositoryContract;
use App\Repositories\DeviceRepository;
use App\Repositories\DeviceRepositoryContract;
use App\Repositories\DeviceTypeRepository;
use App\Repositories\DeviceTypeRepositoryContract;
use App\Repositories\ImageRepository;
use App\Repositories\ImageRepositoryContract;
use App\Repositories\ImageTypeRepository;
use App\Repositories\ImageTypeRepositoryContract;
use App\Repositories\InstallationRepository;
use App\Repositories\InstallationRepositoryContract;
use App\Repositories\IotSourceRepository;
use App\Repositories\IotSourceRepositoryContract;
use App\Repositories\LightwaveRepository;
use App\Repositories\LightwaveRepositoryContract;
use App\Repositories\LiteipDrawingRepository;
use App\Repositories\LiteipDrawingRepositoryContract;
use App\Repositories\LiteipRepository;
use App\Repositories\LiteipRepositoryContract;
use App\Repositories\OwnerRepository;
use App\Repositories\OwnerRepositoryContract;
use App\Repositories\PermissionRepository;
use App\Repositories\PermissionRepositoryContract;
use App\Repositories\RoleRepository;
use App\Repositories\RoleRepositoryContract;
use App\Repositories\SpaceRepository;
use App\Repositories\SpaceRepositoryContract;
use App\Repositories\TelemetryRepository;
use App\Repositories\TelemetryRepositoryContract;
use App\Repositories\TokenRepository;
use App\Repositories\TokenRepositoryContract;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryContract;
use Illuminate\Support\ServiceProvider;

class RinvexContractProvider extends ServiceProvider
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
        $this->app->bind(ActivityRepositoryContract::class, ActivityRepository::class);
        $this->app->bind(ActivityTypeRepositoryContract::class, ActivityTypeRepository::class);
        $this->app->bind(AddressRepositoryContract::class, AddressRepository::class);
        $this->app->bind(CommandRepositoryContract::class, CommandRepository::class);
        $this->app->bind(DeviceLightWaveRepositoryContract::class, DeviceLightWaveRepository::class);
        $this->app->bind(DeviceLiteipRepositoryContract::class, DeviceLiteipRepository::class);
        $this->app->bind(DeviceLiteIpStatusRepositoryContract::class, DeviceLiteIpStatusRepository::class);
        $this->app->bind(DeviceRepositoryContract::class, DeviceRepository::class);
        $this->app->bind(DeviceTypeRepositoryContract::class, DeviceTypeRepository::class);
        $this->app->bind(ImageRepositoryContract::class, ImageRepository::class);
        $this->app->bind(ImageTypeRepositoryContract::class, ImageTypeRepository::class);
        $this->app->bind(InstallationRepositoryContract::class, InstallationRepository::class);
        $this->app->bind(IotSourceRepositoryContract::class, IotSourceRepository::class);
        $this->app->bind(LightwaveRepositoryContract::class, LightwaveRepository::class);
        $this->app->bind(LiteipDrawingRepositoryContract::class, LiteipDrawingRepository::class);
        $this->app->bind(LiteipRepositoryContract::class, LiteipRepository::class);
        $this->app->bind(OwnerRepositoryContract::class, OwnerRepository::class);
        $this->app->bind(PermissionRepositoryContract::class, PermissionRepository::class);
        $this->app->bind(RoleRepositoryContract::class, RoleRepository::class);
        $this->app->bind(SpaceRepositoryContract::class, SpaceRepository::class);
        $this->app->bind(TelemetryRepositoryContract::class, TelemetryRepository::class);
        $this->app->bind(TokenRepositoryContract::class, TokenRepository::class);
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
    }
}
