<?php
namespace App\Services\IoT\Lightwave;

use Eloquent;
use DB;
use GuzzleHttp\Client;

class Authenticate
{

    private $clientId;
    private $clientSecret;
    private $authUrl;


    /**
     * ConsumeTool constructor.
     */
    public function __construct($clientId, $clientSecret, $authUrl)
    {
        $this->setClientId($clientId);
        $this->setClientSecret($clientSecret);
        $this->setAuthUrl($authUrl);
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param mixed $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return mixed
     */
    public function getAuthUrl()
    {
        return $this->authUrl;
    }

    /**
     * @param mixed $authUrl
     */
    public function setAuthUrl($authUrl)
    {
        $this->authUrl = $authUrl;
    }


    /**
     * request access token from LW provider
     * @return mixed
     * @throws \Exception
     */
    public function requestAccessToken() {
        $client = new Client();

        $response = $client->post($this->getAuthUrl(), array (
            'form_params' => array (
                'grant_type' => 'client_credentials',
                'scope' => 'lwcom',
                'client_id' => $this->getClientId(),
                'client_secret' => $this->getClientSecret()
            )
        ));

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Could not get token from provider');
        }

        return (json_decode($response->getBody()));
    }


}