<?php declare(strict_types=1);
session_start();
set_time_limit(7000);

require("src/Weather/WeatherInterface.php");
require("src/Weather/OpenWeatherMap.php");
require("src/Storage/TokenStorageInterface.php");
require("src/Storage/FileStorage.php");
require("src/Storage/SessionStorage.php");
require("src/Sms/SmsInterface.php");
require("src/Sms/Routee.php");
require("src/MainClass.php");
require("src/SendRequests/SendRequestsInterface.php");
require("src/SendRequests/CUrl.php");

use App\Weather\RestApi\OpenWeatherMap;
use App\Storage\SessionStorage;
use App\Sms\RestApi\Routee;
use App\SendRequests\CUrl;

define('WEATHER_APP_ID', 'b385aa7d4e568152288b3c9f5c2458a5');
define('SMS_APP_ID', '5c5d5e28e4b0bae5f4accfec');
define('SMS_SECRET', 'MGkNfqGud0');

$phoneNumber="+306911111111";
$maxTemp=20.00;
$city="Thessaloniki";
$from="TestSender";
$numberOfTries=10;
$secondsDelay=600;


try {
    // Uses OpenWeatherMap Class to retrieve Weather data
    // uses Routee to send Sms
    // uses CURL to send request
    $processRequest= new App\MainClass(new OpenWeatherMap(WEATHER_APP_ID, new CUrl()),new Routee(SMS_APP_ID,SMS_SECRET,new SessionStorage(), new CUrl()));

    // tries  $numberOfTries to send sms with the weather info with delay $secondsDelay
    for($i=0; $i<$numberOfTries; $i++){

        $result=$processRequest->informClient($city, $maxTemp, $phoneNumber, $from);
        echo "Try  :".($i+1)."<br>";
        if(!$result->is_error){
            echo "Sms Sent to ".$phoneNumber." . Status: ".$result->messageStatus."<br>";
        }else{
            echo "Sms not Sent to ".$phoneNumber."<br>Error Message:".$result->errorMessage."<br>";
        }
        echo "<br>";
        sleep($secondsDelay);
    }

}
//catch exception
catch(Exception $e) {
    echo 'Exception Message: ' .$e->getMessage();
}





 
