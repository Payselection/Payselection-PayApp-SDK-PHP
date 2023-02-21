<?php

namespace PaySelection\Response;

use PaySelection\BaseRequest;
use Psr\Http\Message\ResponseInterface;

class WebPayResponse extends BaseRequest
{
    public string $redirectUrl;

    /**
     *
     */
    public function fillByResponse(ResponseInterface $response): self
    {
        $responseBody = json_decode($response->getBody());
        $this->redirectUrl = trim($responseBody ?? '', '"');

        return $this;
    }
}
