<?php

namespace PaySelection\Request;

use PaySelection\BaseRequest;

class StatusRequest extends BaseRequest
{
    public string $id;
    
    function __construct($id) {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function makeRequest(): array
    {
        return [
            'id' => $this->id
        ];
    }
}
