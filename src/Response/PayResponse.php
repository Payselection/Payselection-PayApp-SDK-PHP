<?php

namespace PaySelection\Response;

use PaySelection\BaseRequest;
use Psr\Http\Message\ResponseInterface;

class PayResponse extends BaseRequest
{
    public string $redirectUrl;

    /**
     *
     */
    public function fillByResponse(ResponseInterface $response): self
    {

        // if (is_wp_error($response)) {
        //     return $response;
        // }

        // $response["body"] = json_decode($response["body"], true);

        // $code = $response["response"]["code"];

        // if ($code === 200 || $code === 201) {
        //     return $response["body"];
        // }

        // return 'error';

        $responseBody = json_decode($response->getBody());
        //var_dump($responseBody);
        $this->TransactionId = $responseBody->TransactionId;
        $this->OrderId = $responseBody->OrderId;
        $this->Amount = $responseBody->Amount;
        $this->Currency = $responseBody->Currency;

        

        return $this;
    }
}
