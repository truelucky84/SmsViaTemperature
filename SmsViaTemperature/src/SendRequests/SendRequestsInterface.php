<?php
/**
 * SendRequestsInterface is an interface for sending data
 */

namespace App\SendRequests;

/**
 * Interface SendRequestsInterface
 * @package App\SendRequests
 */
interface SendRequestsInterface
{
    /**
     * sendRequest function sends a Request
     * @param $url
     * @param string $method
     * @param string $data
     * @param $headers
     * @return mixed
     */
    public function sendRequest(
        $url,
        $method,
        $data,
        $headers

    );
}
