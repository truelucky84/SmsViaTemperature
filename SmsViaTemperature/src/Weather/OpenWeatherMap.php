<?php declare(strict_types=1);

namespace App\Weather\RestApi;

use Exception;
use stdClass;
use App\SendRequests\SendRequestsInterface;

/*
 * Weather REST API Client
 *
 * Documentation
 * https://openweathermap.org/current
 *
 */

class OpenWeatherMap implements WeatherInterface
{

    private $apiUrl = 'https://api.openweathermap.org/data/2.5';
    private $appId;
    private $sendRequest;

    /**
     * OpenWeatherMap constructor.
     * @param $appId
     * @param SendRequestsInterface|null $sendRequest
     * @throws Exception
     */
    public function __construct($appId, SendRequestsInterface $sendRequest = null)
    {
        if (empty($appId)) {
            throw new Exception('Empty AppId');
        }
        if ($sendRequest === null) {
            $sendRequest = new CUrl();
        }
        $this->appId = $appId;
        $this->sendRequest = $sendRequest;

    }

    /**
     * @param string $city
     * @return stdClass
     */
    public function getWeatherByCity($city)
    {
        $data = array();
        $data['appid'] = $this->appId;
        $data['q'] = $city;
        $data['units'] = "metric";

        return $this->sendRequest->sendRequest($this->apiUrl . '/weather', 'GET', http_build_query($data), null);
    }


}