<?php declare(strict_types=1);

namespace App\SendRequests;

use stdClass;


/**
 * Class CUrl
 * @package App\SendRequests
 *  * Curl Class implements SendRequestsInterface and sends data via Curl
 */
class CUrl implements SendRequestsInterface
{

    /**
     * @param $url
     * @param string $method
     * @param string $data
     * @param $headers
     * @return mixed|stdClass
     */
    public function sendRequest(
        $url,
        $method = 'GET',
        $data = '',
        $headers

    ) {
        $method = strtoupper($method);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        switch ($method) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if (!empty($data)) {
                    $url .= '?' . $data;
                }
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 300);
        curl_setopt($curl, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        $response = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headerCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $responseBody = substr($response, $header_size);
        $responseHeaders = substr($response, 0, $header_size);
        $ip = curl_getinfo($curl, CURLINFO_PRIMARY_IP);
        $curlErrors = curl_error($curl);
        curl_close($curl);
        $retval = new stdClass();
        $retval->data = json_decode($responseBody);
        $retval->http_code = $headerCode;
        $retval->headers = $responseHeaders;
        $retval->ip = $ip;
        $retval->curlErrors = $curlErrors;
        $retval->method = $method . ':' . $url;
        $retval->timestamp = date('Y-m-d h:i:sP');

        return $retval;
    }

}