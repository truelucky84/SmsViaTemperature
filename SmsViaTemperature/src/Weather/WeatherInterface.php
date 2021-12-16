<?php
namespace App\Weather\RestApi;

/**
 * Interface WeatherInterface
 * @package App\Weather\RestApi
 */
interface WeatherInterface
{
    /**
     * Get Weather Information for a city
     *
     * @param $city string
     *
     */
    public function getWeatherByCity($city);

}
