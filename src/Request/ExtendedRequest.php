<?php

namespace PaySelection\Request;

use PaySelection\BaseRequest;

class ExtendedRequest extends BaseRequest
{
    public array $request;

    /**
     * ExtendedRequest constructor.
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function makeRequest(): array
    {
        return $this->request;
    }
}