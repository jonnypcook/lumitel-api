<?php
namespace App\Services\IoT\Lightwave;

use App\Models\Device;
use App\Services\IoT\IotDataQueryable;
use App\Services\IoT\IotTokenable;
use App\Services\IoT\Token;
use Eloquent;
use DB;
use GuzzleHttp\Client;
use Iterator;

class Data implements IotTokenable, IotDataQueryable, Iterator
{
    use Token;

    /**
     * @var int
     */
    private $totalResults = 0;

    /**
     * @var int
     */
    private $resultsPerPage = 30;

    /**
     * @var int
     */
    private $pageNumber = 1;

    /**
     * @var int
     */
    private $totalPages = 1;

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
     * @var Device
     */
    private $device;

    /**
     * @var string
     */
    private $type;

    /**
     * @return Device
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @param Device $device
     */
    public function setDevice($device)
    {
        $this->device = $device;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }



    /**
     * @return \DateTime
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param \DateTime $from
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
     */
    public function setPageNumber($pageNumber)
    {
        $this->pageNumber = $pageNumber;
    }

    /**
     * @return int
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * @param int $totalPages
     */
    public function setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;
    }

    /**
     * @return int
     */
    public function getTotalResults()
    {
        return $this->totalResults;
    }

    /**
     * @param int $totalResults
     */
    public function setTotalResults($totalResults)
    {

        $this->totalResults = $totalResults;
        $this->setTotalPages(ceil($totalResults / $this->getResultsPerPage()));
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
            throw new \Exception('service failed to accept request');
        }

        return json_decode($response->getBody(), $assoc);
    }

    /**
     * @param Device $device
     * @param $type
     * @param array ...$configuration
     * @return mixed
     * @throws \Exception
     */
    public function getDeviceData(Device $device, $type, ...$configuration)
    {
        $token = $this->getToken();
        $client = new Client();

        $url = sprintf($this->getUri(), $device->provider->vendor_id, $type);


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

        return json_decode($response->getBody(), !empty($configuration[0]));
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        $data = $this->getDeviceData($this->getDevice(), $this->getType(), true);

        if (!empty($data['paginator']) && !empty($data['paginator']['totalRecords'])) {
            $this->setTotalResults((int)$data['paginator']['totalRecords']);
        }

        return $data['rows'];
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->setPageNumber($this->getPageNumber() + 1);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->getPageNumber();
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return
            !empty($this->device) &&
            !empty($this->type) &&
            ($this->getPageNumber() <= $this->getTotalPages());
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->setPageNumber(1);
    }
}