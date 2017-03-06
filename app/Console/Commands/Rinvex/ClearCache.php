<?php

namespace App\Console\Commands\Rinvex;

use App\Models\Installation;
use App\Models\Lightwave;
use App\Models\Owner;
use App\Repositories\ActivityRepository;
use App\Repositories\ActivityTypeRepository;
use App\Repositories\AddressRepository;
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
use App\Repositories\TokenRepository;
use App\Repositories\UserRepository;
use App\Services\IoT\IotDiscoverable;
use App\Services\IoT\Lightwave\Data;
use App\Services\IoT\Lightwave\Discover;
use App\Services\IoT\Lightwave\Command\Light;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Validator;

class ClearCache extends Command
{
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
        TokenRepository $tokenRepository,
        UserRepository $userRepository
    )
    {
        $this->info('Starting Rinvex repository cache clean');

        $this->info(sprintf('removing %s cache', get_class($activityRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $activityRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($activityTypeRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $activityTypeRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($addressRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $addressRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($deviceLightWaveRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $deviceLightWaveRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($deviceLiteipRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $deviceLiteipRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($deviceLiteIpStatusRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $deviceLiteIpStatusRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($deviceRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $deviceRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($deviceTypeRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $deviceTypeRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($imageRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $imageRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($imageTypeRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $imageTypeRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($installationRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $installationRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($iotSourceRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $iotSourceRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($lightwaveRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $lightwaveRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($liteipDrawingRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $liteipDrawingRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($liteipRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $liteipRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($ownerRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $ownerRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($permissionRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $permissionRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($roleRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $roleRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($spaceRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $spaceRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($tokenRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $tokenRepository->forgetCache();

        $this->info(sprintf('removing %s cache', get_class($userRepository)), OutputInterface::VERBOSITY_VERBOSE);
        $userRepository->forgetCache();

        $this->info('Finished');
    }



}
