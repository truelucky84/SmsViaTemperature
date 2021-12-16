<?php declare(strict_types=1);
namespace App\Sms\RestApi;
use Exception;
use App\Storage\TokenStorageInterface;
use App\SendRequests\SendRequestsInterface;

/**
 * Class Routee
 * @package App\Sms\RestApi
 *
 * Documentation
 * https://docs.routee.net/docs/
 */
class Routee implements SmsInterface
{
    private $apiUrl = 'https://connect.routee.net';
    private $authUrl = 'https://auth.routee.net/oauth/token';
    private $appId;
    private $appSecret;
    private $token;
    private $refreshToken = 0;
    private $tokenStorage;
    private $sendRequest;

    /**
     * Routee constructor.
     * @param $appId
     * @param $appSecret
     * @param TokenStorageInterface|null $tokenStorage
     * @param SendRequestsInterface|null $sendRequest
     * @throws Exception
     */
    public function __construct(
        $appId,
        $appSecret,
        TokenStorageInterface $tokenStorage = null,
        SendRequestsInterface $sendRequest = null
    ) {
        if ($tokenStorage === null) {
            $tokenStorage = new SessionStorage();
        }
        if ($sendRequest === null) {
            $sendRequest = new CUrl();
        }
        if (empty($appId)) {
            throw new Exception('Empty AppId');
        }
        if (empty($appSecret)) {
            throw new Exception('Empty AppSecret');
        }
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->tokenStorage = $tokenStorage;
        $this->sendRequest = $sendRequest;
        $authCode = base64_encode($appId . ':' . $appSecret);

        /** load token from storage */
        $this->token = $this->tokenStorage->get($authCode);

        if (empty($this->token) && !$this->getToken()) {
            throw new Exception('Could not connect to api, check authentication');
        }

    }

    /**
     * @param $message
     * @param $number
     * @param $from
     * @return mixed
     * @throws Exception
     */
    public function sendSms($message, $number, $from)
    {
        if (empty($message)) {
            throw new Exception('Empty Sms Message');
        }
        if (empty($number)) {
            throw new Exception('Empty Phone Number');
        }
        if (empty($from)) {
            throw new Exception('Empty Sms From Name');
        }

        $data = array();
        $data['body'] = $message;
        $data['to'] = $number;
        $data['from'] = $from;
        $headers = array(
            "authorization: Bearer " . $this->token,
            "content-type: application/json"
        );
        $result= $this->sendRequest->sendRequest($this->apiUrl . '/sms', 'POST', json_encode($data), $headers);
        if ($result->http_code === 401  ) {
            $this->getToken();
            $headers = array(
                "authorization: Bearer " . $this->token,
                "content-type: application/json"
            );
            $result= $this->sendRequest->sendRequest($this->apiUrl . '/sms', 'POST', json_encode($data), $headers);
        }
        return $result;
    }


    /**
     * @return bool
     */
    private function getToken()
    {
        $data = array(
            'grant_type' => 'client_credentials'

        );
        $authCode = base64_encode($this->appId . ':' . $this->appSecret);
        $headers = array('Authorization: Basic ' . $authCode, 'Expect:');
        $requestResult = $this->sendRequest->sendRequest($this->authUrl, 'POST', http_build_query($data), $headers);
        if ($requestResult->http_code !== 200) {
            return false;
        }
        $this->refreshToken = 0;
        $this->token = $requestResult->data->access_token;
        /** Save token to storage */
        $this->tokenStorage->set($authCode, $this->token);
        return true;
    }


}