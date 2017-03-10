<?php
namespace App\Services\IoT\Lightwave;

use App\Services\IoT\IotTokenable;
use App\Services\IoT\Token;
use Eloquent;
use DB;
use Faker\Provider\cs_CZ\DateTime;
use GuzzleHttp\Client;

class Data implements IotTokenable
{
    use Token;

    /**
     * @var int
     */
    private $resultsPerPage = 30;

    /**
     * @var int
     */
    private $pageNumber = 1;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var \DateTime
     */
    private $from;

    /**
     * @var \DateTime
     */
    private $to;

    /**
     * @return \DateTime
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param \DateTime $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return \DateTime
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param \DateTime $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }


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
    private function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return int
     */
    public function getResultsPerPage()
    {
        return $this->resultsPerPage;
    }

    /**
     * @param int $resultsPerPage
     */
    public function setResultsPerPage($resultsPerPage)
    {
        $this->resultsPerPage = $resultsPerPage;
    }

    /**
     * @return int
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * @param int $pageNumber
     */
    public function setPageNumber($pageNumber)
    {
        $this->pageNumber = $pageNumber;
    }


    /**
     * Data constructor.
     * @param $clientId
     * @param $uri
     */
    public function __construct($clientId, $uri)
    {
        $this->setClient($clientId);
        $this->setUri($uri);
        $this->setFrom(new \DateTime());
        $this->setTo(new \DateTime());
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
     * @param bool $assoc
     * @return mixed
     * @throws \Exception
     */
    public function getData($deviceId, $dataType, $assoc = false)
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

        return json_decode($response->getBody(), $assoc);
    }

}