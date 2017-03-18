<?php
namespace App\Services\IoT\Lightwave;

use App\Models\Device;
use App\Models\DeviceLightWave;
use App\Services\IoT\IotCommandable;
use App\Services\IoT\IotTokenable;
use App\Services\IoT\Token;
use Eloquent;
use DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

abstract class Command implements IotTokenable, IotCommandable
{
    use Token;

    /**
     * @var \App\Models\Device
     */
    private $device;

    /**
     * @var string
     */
    private $uri;

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }


    /**
     * Command constructor.
     * @param $clientId
     * @param $uri
     * @param Device $device
     */
    public function __construct($clientId, $uri, Device $device)
    {
        $this->setClient($clientId);
        $this->setUri($uri);
        $this->setDevice($device);
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
     * @param $command
     * @param $action
     * @param string $value
     * @return mixed
     * @throws \Exception
     */
    public function sendCommand($command, $action, $value = '')
    {
        $token = $this->getToken();
        $client = new Client();

        $url = sprintf($this->getUri(), $this->getLightwaveId(), $command, $action, $value);

        $response = $client->post($url, array(
            'verify' => false,
            'allow_redirects' => true,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ],
        ));


        if ($response->getStatusCode() !== 200) {
            throw new \Exception('service failed to accept request');
        }

        return json_decode($response->getBody(), true);
    }


    /**
     * @param $name
     * @return \App\Services\IoT\Lightwave\Command
     */
    static function factory($name, Device $device) {
        $class = '\App\Services\IoT\Lightwave\Command\\' . $name;
        return new $class(
            Config::get('iot.litewaverf.credentials.clientId'),
            Config::get('iot.litewaverf.api.command'),
            $device);
    }

    /**
     * @param \App\Models\Device $device
     */
    public function setDevice(Device $device)
    {
        $this->device = $device;
    }

    /**
     * @return \App\Models\Device
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @return int
     */
    public function getLightwaveId() {
        return $this->getLightwaveDevice()->vendor_id;
    }

    /**
     * @return \App\Models\DeviceLightWave
     */
    public function getLightwaveDevice() {
        return $this->getDevice()->provider;
    }
}