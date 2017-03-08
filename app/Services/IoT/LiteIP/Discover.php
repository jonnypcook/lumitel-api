<?php
namespace App\Services\IoT\LiteIP;


use App\Models\Address;
use App\Models\Device;
use App\Models\DeviceLiteIp;
use App\Models\Installation;
use App\Models\IotSource;
use App\Models\Liteip;
use App\Models\Owner;
use App\Models\Space;
use App\Repositories\DeviceLiteipRepository;
use App\Repositories\DeviceTypeRepository;
use App\Repositories\IotSourceRepository;
use App\Repositories\SpaceRepository;
use App\Services\IoT\Exceptions\InvalidMappingException;
use App\Services\IoT\Exceptions\MultipleResultsException;
use App\Services\IoT\Exceptions\NotFoundException;
use App\Services\IoT\IotDiscoverable;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Eloquent;
use DB;

class Discover implements IotDiscoverable
{
    /**
     * @var string
     */
    private $discoverProjectUri;

    /**
     * @var string
     */
    private $discoverDrawingUri;

    /**
     * @var string
     */
    private $discoverDeviceUri;

    /**
     * Discover constructor.
     * @param $discoverProjectUri
     * @param $discoverDrawingUri
     * @param $discoverDeviceUri
     */
    public function __construct($discoverProjectUri, $discoverDrawingUri, $discoverDeviceUri)
    {
        $this->discoverProjectUri = $discoverProjectUri;
        $this->discoverDrawingUri = $discoverDrawingUri;
        $this->discoverDeviceUri = $discoverDeviceUri;
    }

    /**
     * @return mixed
     */
    public function getDiscoverProjectUri()
    {
        return $this->discoverProjectUri;
    }

    /**
     * @param mixed $discoverProjectUri
     */
    public function setDiscoverProjectUri($discoverProjectUri)
    {
        $this->discoverProjectUri = $discoverProjectUri;
    }

    /**
     * @return mixed
     */
    public function getDiscoverDrawingUri()
    {
        return $this->discoverDrawingUri;
    }

    /**
     * @param mixed $discoverDrawingUri
     */
    public function setDiscoverDrawingUri($discoverDrawingUri)
    {
        $this->discoverDrawingUri = $discoverDrawingUri;
    }

    /**
     * @return mixed
     */
    public function getDiscoverDeviceUri()
    {
        return $this->discoverDeviceUri;
    }

    /**
     * @param mixed $discoverDeviceUri
     */
    public function setDiscoverDeviceUri($discoverDeviceUri)
    {
        $this->discoverDeviceUri = $discoverDeviceUri;
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
     * @param Owner $owner
     * @param Installation $installation
     * @param mixed $vendorIdentifier
     * @return string
     */
    public function discover(Owner $owner, Installation $installation, $vendorIdentifier)
    {
        $results = ['added' => 0, 'updated' => 0];

        // attach to source
        $this->attachIotSource($installation, $vendorIdentifier['project']);

        // find root space
        $rootSpace = $this->attachRootSpace($installation);

        // start discovery process
        foreach ($vendorIdentifier['drawings'] as $drawing) {
            $devices = $this->findDevices($drawing->DrawingID);
            if (empty($devices)) {
                continue;
            }

            // create the space
            $mapping = !empty($vendorIdentifier['mappings'][$drawing->DrawingID]) ? $vendorIdentifier['mappings'][$drawing->DrawingID] : false;
            $space = $this->attachFloorSpace($rootSpace, $drawing, $mapping);

            $deviceLiteipRepository = new DeviceLiteipRepository();
            foreach ($devices as $device) {
                $lipDevices = $deviceLiteipRepository
                    ->setCacheLifetime(0)
                    ->where('vendor_id', '=', $device->DeviceID)
                    ->findAll();

                if ($lipDevices->isNotEmpty()) {
                    $results['updated'] ++;
                    continue;
                }

                $deviceType = $this->findDeviceType($device);
                DB::transaction(function() use ($device, $deviceType, $space, &$results)
                {
                    $deviceSetup = [
                        'vendor_id' => $device->DeviceID,
                        'serial' => $device->DeviceSN,
                        'profile_id' => $device->ProfileID,
                        'emergency' => $device->IsE3,
                    ];

                    if (preg_match('/^[\d]+[\\/][\d]+[\\/][\d]+[ ][\d]+[:][\d]+[:][\d]+$/', $device->LastE3StatusDate)) {
                        $deviceSetup['emergency_checked'] = \DateTime::createFromFormat('d/m/Y H:i:s', $device->LastE3StatusDate);
                    }

                    if ((int)$device->LastE3Status >= 0) {
                        $deviceSetup['device_liteip_status_id'] = ((int)$device->LastE3Status == 0) ? 600 : (int)$device->LastE3Status;
                    }

                    $liteIpDevice = DeviceLiteIp::create($deviceSetup);

                    Device::create([
                        'device_type_id' => $deviceType->device_type_id,
                        'space_id' => $space->space_id,
                        'provider_id' => $liteIpDevice->device_liteip_id,
                        'provider_type' => get_class($liteIpDevice),
                        'label' => $device->DeviceSN,
                        'emergency' => false,
                        'x' => 0,
                        'y' => 0,
                    ]);

                    $results['added'] ++;

                });
            }
//            DB::transaction(function() use ($devices, $drawing, $installation)
//            {
//            }

        }

        return $results;
    }

    /**
     * @param $device
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function findDeviceType ($device) {
        $deviceTypeRepository = new DeviceTypeRepository();
        switch ($device->ProfileID) {
            case 106: return $deviceTypeRepository->find(2);
            case 107: case 108: case 109:
                return $deviceTypeRepository->find(9);
            default: return $deviceTypeRepository->find(600);
        }

    }

    /**
     * @param Space $rootSpace
     * @param $drawing
     * @param $mappingId
     * @return \Illuminate\Database\Eloquent\Model|mixed
     * @throws InvalidMappingException
     */
    protected function attachFloorSpace(Space $rootSpace, $drawing, $mappingId) {
        $spaceRepository = new SpaceRepository();

        // check to see if it already exists
        $spaces = $spaceRepository
            ->setCacheLifetime(0)
            ->where('installation_id', '=', $rootSpace->installation_id)
            ->where('parent_id', '=', $rootSpace->space_id)
            ->where('level', '!=')
            ->where('vendor_id', '=', $mappingId)
            ->orderBy('level', 'asc')
            ->limit(1)
            ->findAll();

        if ($spaces->isNotEmpty()) {
            return $spaces->first();
        }

        // check for mapping requirements
        if (!empty($mappingId)) {
            $space = $spaceRepository
                ->setCacheLifetime(0)
                ->find($mappingId);

            if (empty($space)) {
                throw new InvalidMappingException(sprintf('Could not find mapping for drawing %s to space #%d', $drawing->Drawing, $mappingId));
            }

            $space->vendor_id = $drawing->DrawingID;
            $space->save();
        } else {
            $name = preg_replace('/([.][^.]+)+$/', '', $drawing->Drawing);
            // create new space
            $createdEntity = $spaceRepository->create([
                'installation_id' => $rootSpace->installation_id,
                'parent_id' => $rootSpace->space_id,
                'vendor_id' => $drawing->DrawingID,
                'name' => preg_replace('/([.][^.]+)+$/', '', $name),
                'description' => preg_replace('/([.][^.]+)+$/', '', $name),
                'level' => $this->detectFloorNumber($name),
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
     * find floor number
     * @param $name
     * @return int
     */
    public function detectFloorNumber($name) {
        $matches = array();
        if (preg_match('/([ ]|^)([\d]+)(th|st|nd|rd)/', $name, $matches)) {
            return (int)$matches[2];
        }

        if (preg_match('/(one|first)/', $name)) return 1;
        if (preg_match('/(two|second)/', $name)) return 2;
        if (preg_match('/(three|third)/', $name)) return 3;
        if (preg_match('/(four|fourth)/', $name)) return 4;
        if (preg_match('/(five|fifth)/', $name)) return 5;
        if (preg_match('/(six|sixth)/', $name)) return 6;
        if (preg_match('/(seven|seventh)/', $name)) return 7;
        if (preg_match('/(eight|eighth)/', $name)) return 8;
        if (preg_match('/(nine|ninth)/', $name)) return 9;
        if (preg_match('/(ten|tenth)/', $name)) return 10;


        return 0;
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
     * attach or update IoT Source information
     * @param Installation $installation
     * @param $project
     */
    protected function attachIotSource(Installation $installation, $project) {
        // check for existing iotSource
        $iotSourceRepository = new IotSourceRepository();
        $iotSources = $iotSourceRepository
            ->setCacheLifetime(0)
            ->with(['provider'])
            ->where('installation_id', '=', $installation->installation_id)
            ->where('provider_type', '=', Liteip::class)
            ->limit(1)
            ->findAll();

        if (!$iotSources->isEmpty()) {
            $source = $iotSources->first();
            $this->configureLiteIP($source->provider, $project);
            $source->provider->save();
        } else {
            //TODO: need to ensure that this use repository as it will not cache results properly otherwise
            DB::transaction(function() use ($installation, $project)
            {
                $liteip = new Liteip();
                $this->configureLiteIP($liteip, $project);
                $liteip->save();

                $iotSource = new IotSource();
                $iotSource->installation_id = $installation->installation_id;
                $iotSource->provider_id = $liteip->liteip_id;
                $iotSource->provider_type = get_class($liteip);
                $iotSource->save();
            });
        }
    }

    /**
     * @param Liteip $liteip
     * @param $project
     */
    private function configureLiteIP(Liteip $liteip, $project) {
        $liteip->vendor_id = $project->ProjectID;
        $liteip->customer_id = $project->CustomerGroup;
        $liteip->postcode = $project->PostCode;
        $liteip->description = $project->ProjectDescription;
        $liteip->active = true;
    }

    /**
     * query liteip API gateway
     * @param $gatewayUrl
     * @param array $query
     * @return mixed
     * @throws \Exception
     */
    private function getApiData($gatewayUrl, $query = array()) {
        $client = new Client();

        $options = array(
            'verify' => false,
            'allow_redirects' => true
        );

        if (!empty($query)) {
            $options['query'] = $query;
        }

        $response = $client->get($gatewayUrl, $options);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('service failed to respond on: ' . $gatewayUrl);
        }

        return json_decode($response->getBody());
    }

    /**
     * @param $drawingId
     * @return mixed
     */
    public function findDevices($drawingId) {
        $devices = $this->getApiData($this->getDiscoverDeviceUri(), ['DrawingID' => $drawingId, 'E3Only' => 0]);

        return $devices;
    }


    /**
     * @param $projectId
     * @return mixed
     * @throws NotFoundException
     */
    public function findDrawings($projectId) {
        $drawings = $this->getApiData($this->getDiscoverDrawingUri(), ['ProjectID' => $projectId]);

        if (empty($drawings)) {
            throw new NotFoundException('Could not find any drawings for project');
        }

        return $drawings;
    }


    /**
     * @param $postcode
     * @return mixed
     * @throws MultipleResultsException
     * @throws NotFoundException
     */
    public function findProject($postcode) {
        $projectData = $this->getApiData($this->getDiscoverProjectUri());

        $projects = array_filter($projectData, function ($project) use($postcode) {
            return $project->PostCode == $postcode;
        });

        if (empty($projects)) {
            throw new NotFoundException('Could not find postcode in project data set');
        }

        if (count($projects) !== 1) {
            throw new MultipleResultsException('Postcode appears multiple times in project data set');
        }

        return array_shift($projects);
    }

    /**
     * @param array $liteIpProjectIds
     * @param Owner $owner
     * @return bool
     * @throws \Exception
     */
    public function consumeProject (array $liteIpProjectIds, Owner $owner) {
        try {
            $liteIpProjectIds = (array)$liteIpProjectIds;
            $projectData = $this->getApiData(Config::get('iot.liteip.api.project'));

            $projects = array_filter($projectData, function ($obj) use($liteIpProjectIds) {
                return in_array((int)$obj->ProjectID, $liteIpProjectIds);
            });

            if (empty($projects)) {
                throw new \Exception('Could not find liteIP projects');
            }

            Eloquent::unguard();
            foreach ($projects as $project) {
                DB::transaction(function() use ($project, $owner)
                {
                    $liteip = Liteip::create([
                        'name' => $project->ProjectDescription,
                        'postcode' => $project->PostCode,
                        'vendor_id' => (int)$project->ProjectID,
                        'customer_group' => $project->CustomerGroup,
                        'active' => false,
                        'test' => false
                    ]);

                    $address = Address::create([
                        'postcode' => str_replace('_', ' ' , $project->PostCode)
                    ]);

                    $installation = Installation::create([
                        'name' => $project->ProjectDescription,
                        'address_id' => $address->address_id,
                        'owner_id' => $owner->owner_id,
                        'commissioned' => new \DateTime()
                    ]);

                    IotSource::create([
                        'installation_id' => $installation->installation_id,
                        'provider_id' => $liteip->liteip_id,
                        'provider_type' => Liteip::class,
                    ]);

                });
            }

            return true;

        } catch (\Exception $exception) {
            if ($this->isThrowExceptions() === true) {
                throw $exception;
            }
            return false;
        }
    }

}