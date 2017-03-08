<?php
namespace App\Services\IoT\Lightwave;


use App\Models\Device;
use App\Models\DeviceLightWave;
use App\Models\Installation;
use App\Models\IotSource;
use App\Models\Lightwave;
use App\Models\Owner;
use App\Models\Space;
use App\Repositories\DeviceLightWaveRepository;
use App\Repositories\DeviceTypeRepository;
use App\Repositories\IotSourceRepository;
use App\Repositories\SpaceRepository;
use App\Services\IoT\Exceptions\InvalidDataStructureException;
use App\Services\IoT\Exceptions\NotFoundException;
use App\Services\IoT\IotDiscoverable;
use App\Services\IoT\IotLightwave;
use App\Services\IoT\IotTokenable;
use App\Services\IoT\Token;
use GuzzleHttp\Client;
use Eloquent;
use DB;

class Discover implements IotDiscoverable, IotTokenable
{
    use Token;

    /**
     * Discover constructor.
     * @param $clientId
     * @param $uri
     */
    public function __construct($clientId, $uri)
    {
        $this->setClient($clientId);
        $this->setUri($uri);
    }


    /**
     * @var string
     */
    private $uri;


    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }


    protected $throwExceptions = false;

    /**
     * @return bool
     */
    public function isThrowExceptions()
    {
        return $this->throwExceptions;
    }

    /**
     * @param bool $throwExceptions
     */
    public function setThrowExceptions($throwExceptions)
    {
        $this->throwExceptions = $throwExceptions;
    }

    /**
     * request data from lw api
     * @return mixed
     * @throws \Exception
     */
    public function getApiDiscoveryData() {
        $token = $this->getToken();
        $client = new Client();

        $response = $client->get($this->getUri(), array(
            'verify' => false,
            'allow_redirects' => true,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ]
        ));

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('service failed to respond on: ' . $this->getUri());
        }

        return json_decode($response->getBody(), true);
    }

    /**
     * @param Owner $owner
     * @param Installation $installation
     * @param mixed $vendorIdentifier
     * @return bool
     * @throws \Exception
     */
    public function discover(Owner $owner, Installation $installation, $vendorIdentifier)
    {
        // get data from API
        $discoveredData = $this->getApiDiscoveryData();

        // check for users data structure
        if (empty($discoveredData['users'])) {
            throw new InvalidDataStructureException("users data not found");
        }

        // find user in collection
        $user = $this->findUser($discoveredData['users'], $vendorIdentifier);
        if (empty($user)) {
            throw new NotFoundException(sprintf('user %s not found in users collection', $vendorIdentifier));
        }

        // ensure we only have one instance of this user
        if (count($user) !== 1) {
            throw new InvalidDataStructureException(sprintf('multiple user %s found in users collection (%d occurrences)', $vendorIdentifier, count($user)));
        }

        // get user and userId
        reset($user);
        $userId = key($user);
        $user = array_shift($user);

        // find rooms for user
        $rooms = $this->findUserObjects($discoveredData['room'], $userId);

        // find devices for user
        $devices = $this->findUserObjects($discoveredData['device'], $userId);

        // find timers for user
        $timers = $this->findUserObjects($discoveredData['timer'], $userId);

        // attach to source
        $this->attachIotSource($installation, $user);

        // attach device data to installation
        $this->attachDevices($installation, $devices, $rooms, $timers);

        return true;
    }

    /**
     * @param Installation $installation
     * @param array $devices
     * @param array $rooms
     * @param array $timers
     */
    protected function attachDevices(Installation $installation, array $devices, array $rooms, array $timers) {
        //TODO: use events so that we can report back to console
        $rootSpace = $this->attachRootSpace($installation);
        $floorSpace = $this->attachFloorSpace($rootSpace);

        $deviceLightWaveRepository = new DeviceLightWaveRepository();

        foreach ($devices as $deviceId => $device) {

            $deviceLightWaveRepository->setCacheLifetime(0);
            $lwDevices = $deviceLightWaveRepository->where('vendor_id', '=', $deviceId)->findAll();
            if ($lwDevices->isNotEmpty()) {
                continue;
            }

            $deviceType = $this->findDeviceType($device);
            $space = $this->attachSpace($floorSpace, $device['room_id'], $rooms);
            DB::transaction(function() use ($deviceId, $device, $deviceType, $space)
            {
                $lightwaveDevice = DeviceLightWave::create([
                    'vendor_id' => $deviceId,
                    'wfl_id' => $device['wfl_id'],
                    'dm_id' => $device['dm_id'],
                    'active' => ($device['active'] == 1),
                    'device_number' => $device['device_number'],
                    'serial' => $device['serial'],
                    'rank' => $device['rank'],
                    'energy_rank' => $device['energy_rank'],
                    'trigger_rank' => $device['trigger_rank'],
                    'heating_rank' => $device['heating_rank'],
                    'unit_rate' => (int)$device['unit_rate'],
                    'device_type_name' => $device['device_type_name'],
                    'wfl_code' => $device['wfl_code'],
                    'is_heating' => $device['is_heating'] == 1,
                ]);

                Device::create([
                    'device_type_id' => $deviceType->device_type_id,
                    'space_id' => $space->space_id,
                    'provider_id' => $lightwaveDevice->device_lightwave_id,
                    'provider_type' => get_class($lightwaveDevice),
                    'label' => $device['name'],
                    'emergency' => false,
                    'x' => 0,
                    'y' => 0,
                ]);

            });

        }


    }

    /**
     * @param array $device
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function findDeviceType (array $device) {
        $deviceTypeRepository = new DeviceTypeRepository();
        switch ($device['device_type_id']) {
            case 1: return $deviceTypeRepository->find(7);
            case 8: return $deviceTypeRepository->find(3);
            case 10: return $deviceTypeRepository->find(8);
            case 11: return $deviceTypeRepository->find(4);
            case 13: return $deviceTypeRepository->find(preg_match('/water/i', $device['name']) ? 5 : 6);
            default: return $deviceTypeRepository->find(600);
        }

    }

    protected function attachSpace(Space $floor, $roomId, array $rooms) {
        if (empty($roomId) || empty($rooms[$roomId])) {
            return $floor->parent;
        }

        $room = $rooms[$roomId];

        $spaceRepository = new SpaceRepository();
        $spaces = $spaceRepository
            ->where('installation_id', '=', $floor->installation_id)
            ->where('vendor_id', '=', $room['room_id'])
            ->limit(1)
            ->findAll();

        if ($spaces->isNotEmpty()) {
            return $spaces->first();
        }

        $createdEntity = $spaceRepository->create([
            'installation_id' => $floor->installation_id,
            'parent_id' => $floor->space_id,
            'vendor_id' => $room['room_id'],
            'name' => $room['name'],
            'description' => $room['name'],
            'level' => $floor->level,
            'left' => 0,
            'top' => 0,
        ]);
        list($status, $space) = $createdEntity;

        return $space;
    }

    /**
     * @param Installation $installation
     * @return Space|mixed
     */
    protected function attachRootSpace(Installation $installation) {
        $spaceRepository = new SpaceRepository();
        $spaceRepository->setCacheLifetime(0);
        $spaces = $spaceRepository
            ->where('installation_id', '=', $installation->installation_id)
            ->where('parent_id', '=')
            ->limit(1)
            ->findAll();

        if ($spaces->isNotEmpty()) {
            $space = $spaces->first();
        } else {
            $createdEntity = $spaceRepository->create([
                'installation_id' => $installation->installation_id,
                'name' => 'Root',
                'description' => 'Root',
            ]);
            list($status, $space) = $createdEntity;
        }

        return $space;
    }

    /**
     * @param Space $rootSpace
     * @return Space|mixed
     */
    protected function attachFloorSpace(Space $rootSpace) {
        $spaceRepository = new SpaceRepository();
        $spaceRepository->setCacheLifetime(0);
        $spaces = $spaceRepository
            ->where('installation_id', '=', $rootSpace->installation_id)
            ->where('parent_id', '=', $rootSpace->space_id)
            ->where('level', '!=')
            ->orderBy('level', 'asc')
            ->limit(1)
            ->findAll();

        if ($spaces->isNotEmpty()) {
            $space = $spaces->first();
        } else {
            $createdEntity = $spaceRepository->create([
                'installation_id' => $rootSpace->installation_id,
                'parent_id' => $rootSpace->space_id,
                'name' => 'Ground Floor',
                'description' => 'Ground Floor',
                'level' => 0,
                'width' => 840,
                'height' => 540,
                'left' => 0,
                'top' => 0,
            ]);
            list($status, $space) = $createdEntity;
        }

        return $space;
    }

    /**
     * attach or update IoT Source information
     * @param Installation $installation
     * @param array $user
     */
    protected function attachIotSource(Installation $installation, array $user) {
        // check for existing iotSource
        $iotSourceRepository = new IotSourceRepository();
        $iotSourceRepository->setCacheLifetime(0);
        $iotSources = $iotSourceRepository
            ->with(['provider'])
            ->where('installation_id', '=', $installation->installation_id)
            ->where('provider_type', '=', Lightwave::class)
            ->limit(1)
            ->findAll();

        if (!$iotSources->isEmpty()) {
            $source = $iotSources->first();
            $this->configureLightwave($source->provider, $user);
            $source->provider->save();
        } else {
            //TODO: need to ensure that this use repository as it will not cache results properly otherwise
            DB::transaction(function() use ($installation, $user)
            {
                $lightwave = new Lightwave();
                $lightwave->test = false;
                $this->configureLightwave($lightwave, $user);
                $lightwave->save();

                $iotSource = new IotSource();
                $iotSource->installation_id = $installation->installation_id;
                $iotSource->provider_id = $lightwave->lightwave_id;
                $iotSource->provider_type = get_class($lightwave);
                $iotSource->save();
            });
        }
    }

    /**
     * convenience function used to prepare Lightwave data
     * @param Lightwave $lightwave
     */
    private function configureLightwave(Lightwave $lightwave, $user) {
        $lightwave->vendor_id = $user['lightwaveRfPublic']['user_id'];
        $lightwave->_id = $user['_id'];
        $lightwave->forename = $user['givenName'];
        $lightwave->surname = $user['familyName'];
        $lightwave->email = $user['email'];
        $lightwave->active = $user['lightwaveRfPublic']['active'];
    }

    /**
     * @param array $users
     * @param $vendorIdentifier
     * @return mixed
     */
    public function findUser ($users, $vendorIdentifier) {
        return array_filter($users, function ($user) use ($vendorIdentifier) {
            return $user['email'] === $vendorIdentifier;
        });
    }

    /**
     * find user objects given the user_id
     * @param array $userObjects
     * @param $userId
     * @return array
     */
    public function findUserObjects (array $userObjects, $userId) {
        return array_filter($userObjects, function ($userObject) use ($userId) {
            return $userObject['user_id'] == $userId;
        });
    }

    public function test()
    {
        return __FUNCTION__;
    }

}