<?php

namespace PaySelection\Response;

use PaySelection\BaseRequest;
use PaySelection\Response\Models\BaseModel;
use Psr\Http\Message\ResponseInterface;

class PSResponse extends BaseRequest
{
    public function fillByResponse(ResponseInterface $response): self
    {
        $responseContent = json_decode($response->getBody());

        $this->fill($responseContent);

        return $this;
    }

    public function fill($responseBody)
    {

    }
}
