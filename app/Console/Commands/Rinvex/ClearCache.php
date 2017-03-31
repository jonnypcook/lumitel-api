<?php

namespace App\Console\Commands\Rinvex;

use App\Console\Commands\ConsoleErrors;
use App\Repositories\ActivityRepository;
use App\Repositories\ActivityTypeRepository;
use App\Repositories\AddressRepository;
use App\Repositories\CommandRepository;
use App\Repositories\DeviceHistoryRepository;
use App\Repositories\DeviceHistoryTransactionRepository;
use App\Repositories\DeviceLightWaveRepository;
use App\Repositories\DeviceLiteipRepository;
use App\Repositories\DeviceLiteIpStatusRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\DeviceTypeRepository;
use App\Repositories\ImageRepository;
use App\Repositories\ImageTypeRepository;
use App\Repositories\InstallationRepository;
use App\Repositories\IotSourceRepository;
use App\Repositories\LightwaveRepository;
use App\Repositories\LiteipDrawingRepository;
use App\Repositories\LiteipRepository;
use App\Repositories\OwnerRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SpaceRepository;
use App\Repositories\TelemetryRepository;
use App\Repositories\TokenRepository;
use App\Repositories\UserRepository;
use Illuminate\Console\Command;
use Rinvex\Repository\Repositories\EloquentRepository;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCache extends Command
{
    use ConsoleErrors;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rinvex:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all the Rinvex cached data';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(
        ActivityRepository $activityRepository,
        ActivityTypeRepository $activityTypeRepository,
        AddressRepository $addressRepository,
        CommandRepository $commandRepository,
        DeviceHistoryTransactionRepository $deviceHistoryTransactionRepository,
        DeviceHistoryRepository $deviceHistoryRepository,
        DeviceLightWaveRepository $deviceLightWaveRepository,
        DeviceLiteipRepository $deviceLiteipRepository,
        DeviceLiteIpStatusRepository $deviceLiteIpStatusRepository,
        DeviceRepository $deviceRepository,
        DeviceTypeRepository $deviceTypeRepository,
        ImageRepository $imageRepository,
        ImageTypeRepository $imageTypeRepository,
        InstallationRepository $installationRepository,
        IotSourceRepository $iotSourceRepository,
        LightwaveRepository $lightwaveRepository,
        LiteipDrawingRepository $liteipDrawingRepository,
        LiteipRepository $liteipRepository,
        OwnerRepository $ownerRepository,
        PermissionRepository $permissionRepository,
        RoleRepository $roleRepository,
        SpaceRepository $spaceRepository,
        TelemetryRepository $telemetryRepository,
        TokenRepository $tokenRepository,
        UserRepository $userRepository
    )
    {
        $this->info('Starting Rinvex repository cache clean');

        foreach (func_get_args() as $arg) {
            $this->clearRepositoryCache($arg);
        }

        $this->info('Finished');
    }

    /**
     * @param EloquentRepository $eloquentRepository
     */
    protected function clearRepositoryCache(EloquentRepository $eloquentRepository) {
        $this->info(sprintf('removing %s cache', get_class($eloquentRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $eloquentRepository->forgetCache();
    }



}
