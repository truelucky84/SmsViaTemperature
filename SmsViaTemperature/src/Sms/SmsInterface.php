<?php
namespace App\Sms\RestApi;

/**
 * Interface SmsInterface sends an sms to a mobileNumber
 * @package App\Sms\RestApi
 */
interface SmsInterface
{
    /**
     * @param $message
     * @param $number
     * @param $from
     * @return mixed
     */
    public function sendSms($message, $number, $from);

}
