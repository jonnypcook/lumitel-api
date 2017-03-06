<?php
namespace App\Services\IoT\Lightwave;

use App\Services\IoT\IotTokenable;
use App\Services\IoT\Token;
use Eloquent;
use DB;

abstract class Command implements IotTokenable
{
    use Token;

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
     */
    public function __construct($clientId, $uri)
    {
        $this->setClient($clientId);
        $this->setUri($uri);
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
     * @param $deviceId
     * @param $dataType
     * @return bool
     * @throws \Exception
     */
    public function sendCommand($deviceId, $dataType)
    {
        $token = $this->getToken();
        $client = new Client();

        $url = sprintf($this->getUri(), $deviceId, $dataType);
        $queryData = [
            'from' => $this->getFrom()->format('Y-m-d\TH:i:s'),
            'to' => $this->getTo()->format('Y-m-d\TH:i:s'),
            'perpage' => $this->getResultsPerPage(),
            'page' => $this->getPageNumber()
        ];

        $response = $client->get($url, array(
            'verify' => false,
            'allow_redirects' => true,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ],
            'query' => $queryData
        ));


        if ($response->getStatusCode() !== 200) {
            throw new \Exception('service failed to respond on: ' . $gatewayUrl);
        }

        $deviceData = json_decode($response->getBody());
        print_r($deviceData);

//        return json_decode($response->getBody());
        return true;
    }


}