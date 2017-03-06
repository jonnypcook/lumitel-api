<?php
namespace App\Services\IoT\LiteIP;


use App\Models\Address;
use App\Models\Installation;
use App\Models\IotSource;
use App\Models\Liteip;
use App\Models\Owner;
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
        // TODO: Implement discover() method.
        return __CLASS__;
    }


    /**
     * consume gateway
     * @param $gatewayUrl
     * @return mixed
     * @throws \Exception
     */
    private function consume($gatewayUrl) {
        $client = new Client();
        $response = $client->get($gatewayUrl);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('service failed to respond on: ' . $gatewayUrl);
        }

        return json_decode($response->getBody());

    }

    /**
     * consume drawings given iotSource
     * @param Liteip $liteip
     * @return bool
     * @throws \Exception
     */
    public function consumeDrawings (Liteip $liteip) {
        try {
            echo $liteip->name;

            return true;
        } catch (\Exception $exception) {
            if ($this->isThrowExceptions() === true) {
                throw $exception;
            }
            return false;
        }
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
            $projectData = $this->consume(Config::get('iot.liteip.api.project'));

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