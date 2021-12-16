<?php declare(strict_types=1);

namespace App;

use Exception;
use stdClass;
use App\Weather\RestApi\WeatherInterface;
use App\Sms\RestApi\SmsInterface;

/**
 * Class MainClass
 * @package App
 */
class MainClass
{
    /**
     * @var WeatherInterface
     */
    private $weatherClient;
    /**
     * @var SmsInterface
     */
    private $smsClient;

    /**
     * MainClass constructor.
     * @param WeatherInterface $WeatherClient
     * @param SmsInterface $SmsClient
     * @throws Exception
     */
    public function __construct(WeatherInterface $WeatherClient, SmsInterface $SmsClient)
    {
        if ($WeatherClient === null) {
            throw new Exception('Error');
        }
        if ($SmsClient === null) {
            throw new Exception('Error');
        }
        $this->weatherClient = $WeatherClient;
        $this->smsClient = $SmsClient;
    }

    /**
     * This function retrieves the weather of a city and sends an sms warning to a mobile number
     * @param $city
     * @param $maxTemp
     * @param $number
     * @param $from
     * @return stdClass
     */
    public function informClient($city, $maxTemp, $number, $from)
    {
        $response = new stdClass();
        $response->is_error = false;
        $response->errorMessage = "";
        $response->messageStatus = "";

        $weatherRusults = $this->weatherClient->getWeatherByCity($city);
        if (!empty($weatherRusults->data->main->temp)) {
            $weatherTemperature = floatval($weatherRusults->main->temp);
            $smsMessage = $weatherTemperature > $maxTemp ?   "Your name and Temperature more than " . $maxTemp . "C. " . $weatherRusults->data->main->temp :  "Your name and Temperature less than " . $maxTemp . "C. " . $weatherRusults->data->main->temp;
            $smsResult = $this->smsClient->sendSms($smsMessage, $number, $from);
            if (empty($smsResult->data) || $smsResult->data->is_error) {
                $response->is_error = true;
                $response->errorMessage = "Message not sent. ";
            } else {
                $response->messageStatus = $smsResult->data->status;
            }
        } else {
            $response->is_error = true;
            $response->errorMessage = "Error Retrieving Weather";
        }
        return $response;
    }


}