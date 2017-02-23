<?php
namespace App\Services\LiteIP;


use App\Models\Address;
use App\Models\Installation;
use App\Models\IotSource;
use App\Models\Liteip;
use App\Models\Owner;
use App\Repositories\InstallationRepository;
use App\Repositories\LiteipRepository;
use App\Repositories\OwnerRepository;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Eloquent;
use DB;
class ConsumeTool
{

    /**
     * ConsumeTool constructor.
     */
    public function __construct()
    {

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