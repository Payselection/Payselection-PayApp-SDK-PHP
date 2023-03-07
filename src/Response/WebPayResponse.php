<?php

namespace PaySelection\Response;

use PaySelection\Response\PSResponse;

class WebPayResponse extends PSResponse
{
    public string $redirectUrl;

    public function fill($responseBody) {
        $this->redirectUrl = trim($responseBody ?? '', '"');
    }
}
