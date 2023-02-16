<?php

namespace PaySelection;

use GuzzleHttp\Exception\GuzzleException;
use PaySelection\Enum\PSMethodsEnum;
use PaySelection\Enum\PaymentType;
use PaySelection\Request\WebPayment;
use PaySelection\Response\PSResponse;
use GuzzleHttp\Client;
use PaySelection\Response\WebPayResponse;
use Psr\Http\Message\ResponseInterface;

class Library
{
    protected string  $siteId;
    protected string  $secretKey;
    protected string  $url;
    protected Client  $client;
    private   array   $configParams;

    /**
     * @param string|null $filePath
     */
    public function __construct(string $filePath = null)
    {
        $this->loadConfiguration($filePath);
        $this->url       = $this->configParams['webpay_url'];
        $this->siteId    = $this->configParams['site_id'];
        $this->secretKey = $this->configParams['secret_key'];;

        $this->client = new Client([
            'headers'  => ['X-Site-ID' => $this->siteId],
            'base_uri' => $this->url,
            'expect'   => false
        ]);
    }

    /**
     * https://api.payselection.com/#tag/webpay
     * @param $amount
     * @param string $currency
     * @param string $orderId
     * @param string $description
     * @param array|null $extraData
     * @param array|null $customerInfo
     * @return WebPayResponse
     */
    public function webPayCreate(
        $amount,
        string $currency,
        string $orderId,
        string $description,
        ?array $extraData = array(),
        ?array $customerInfo = array()
    ): WebPayResponse
    {
        $method = PSMethodsEnum::PAYMENTS_WEBPAY;

        $webPaymentData = new WebPayment(PaymentType::PAY);
        $webPaymentData->amount       = $amount;
        $webPaymentData->currency     = $currency;
        $webPaymentData->orderId      = $orderId;
        $webPaymentData->description  = $description;
        $webPaymentData->extraData    = $extraData;
        $webPaymentData->customerInfo = $customerInfo;

        return $this->requestWebPay($method, $webPaymentData->makeRequest());
    }


    /**
     * @param array $request
     * @return WebPayResponse
     * @throws GuzzleException
     */
    public function webPayCreateExtended(array $request): WebPayResponse
    {
        $method = PSMethodsEnum::PAYMENTS_WEBPAY;

        $webPaymentData = new WebPayment(PaymentType::PAY);
        $webPaymentData->request = $request;

        return $this->requestWebPay($method, $webPaymentData->makeRequestExtended());
    }

    /**
     * @param string $method
     * @param array $postData
     * @return PSResponse
     * @throws GuzzleException
     */
    protected function request(string $method, array $postData = []): PSResponse
    {
        $response = $this->sendRequest($method, $postData);

        $psResponse = new PSResponse();
        return $psResponse->fillByResponse($response);
    }


    /**
     * @param string $method
     * @param array $postData
     * @return WebPayResponse
     * @throws GuzzleException
     */
    protected function requestWebPay(string $method, array $postData = []): WebPayResponse
    {
        $response = $this->sendRequest($method, $postData);

        $webPayResponse = new WebPayResponse();
        return $webPayResponse->fillByResponse($response);
    }

    /**
     * @param string $method
     * @param array $postData
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function sendRequest(string $method, array $postData = []): ResponseInterface
    {
        $uid = $this->getIdempotenceKey();
        $msg = 'POST' . PHP_EOL .
            $method . PHP_EOL .
            $this->siteId . PHP_EOL .
            $uid . PHP_EOL .
            json_encode($postData);

        $headers = [
            'X-Request-ID' => $uid,
            'X-Request-Signature' => $this->getSignature($msg, $this->secretKey)
        ];

        $options = ['json' => $postData];
        $options['headers'] = $headers;

        return $this->client->post('/' . $method, $options);
    }

    /**
     * @return string
     */
    private function getIdempotenceKey(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }


    /**
     * @param string $body
     * @param string $secretKey
     * @return string
     */
    private function getSignature(string $body, string $secretKey): string
    {
        return hash_hmac('sha256', $body, $secretKey);
    }

    /**
     * @param $filePath
     * @return $this
     */
    public function loadConfiguration($filePath = null): Library
    {
        if ($filePath) {
            $data = file_get_contents($filePath);
        } else {
            $data = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "configuration.json");
        }

        $paramsArray = json_decode($data, true);
        $this->configParams = $paramsArray;

        return $this;
    }
}
